// npm install --save fast-glob
// npm install imagemin
// npm install imagemin-pngquant
// npm install imagemin-jpegtran
// npm install imagemin-mozjpeg
// npm install imagemin-gifsicle
// npm install imagemin-optipng
// npm install imagemin-svgo


const imagemin = require('imagemin-keep-folder');
const imageminJpegtran = require('imagemin-jpegtran');
const imageminPngquant = require('imagemin-pngquant');
const imageminJpegmozjpeg = require('imagemin-mozjpeg');
const imageminGif = require('imagemin-gifsicle');
const imageminOpipng = require('imagemin-optipng');
const imageminSvgo = require('imagemin-svgo');
const readChunk = require('read-chunk');
const fileType = require('file-type');
const walkSync = require('walk-sync');
const imageminMozjpeg = require('imagemin-mozjpeg');
const fs = require('fs');

// get path folder wanna optimize images
const paths = walkSync('public/uploads', {  directories: false });
var imageOpts =[];
// loop paths
paths.forEach(function(filename) {
	// max file 1MB
	var maxFileSize = 1024 * 1024;
	var filename = 'public/uploads/' + filename;
	// find mime of files
	const buffer = readChunk.sync(filename, 0, fileType.minimumBytes);
	const stats = fs.statSync(filename);
	// get size file
	const fileSizeInBytes = stats.size;
	var type = fileType(buffer);

	// check type undefined 
	if (type === undefined) {
		// set permission for files
		fs.chmodSync(filename, '0777');
		// remove files 
		fs.unlinkSync(filename);
	}
	// check mime need to optimize image
	if (type !== undefined) {
		if (type.mime == 'image/jpeg' || type.mime == 'image/png' || type.mime == 'image/gif' ) {
			if (fileSizeInBytes > maxFileSize) {
		  		imageOpts.push(filename);
			}
		} else {
			// remove files
			if (type.mime != 'image/bmp') {
				fs.unlinkSync(filename);
			}
		}
	}
});

var imageOptChunks = chunkArray(imageOpts, 15);
console.log(imageOptChunks);

console.log('Optimize images processing .....');
(async () => {
	for (let imageOptChunk of imageOptChunks) {
	    try {
		    const files = await imagemin(imageOptChunk, {
				use: [
					imageminMozjpeg(),
					// imageminJpegtran({quality: '65-80'}),
					imageminPngquant(),
					imageminGif(),
					// imageminSvgo()
				]
			});

		    console.log(files);
		    await delay(5000);
		}
		catch(e) {
		    console.log('Catch an error: ', e)
		}
    }
	
	console.log('Optimize images complete!');
})();

/**
 * Returns an array with arrays of the given size.
 *
 * @param myArray {Array} array to split
 * @param chunk_size {Integer} Size of every group
 */
function chunkArray(myArray, chunk_size){
    var index = 0;
    var arrayLength = myArray.length;
    var tempArray = [];
    
    for (index = 0; index < arrayLength; index += chunk_size) {
        myChunk = myArray.slice(index, index+chunk_size);
        // Do something if you want with the group
        tempArray.push(myChunk);
    }

    return tempArray;
}

function delay(t) {
    return new Promise(resolve => setTimeout(resolve, t));
}

