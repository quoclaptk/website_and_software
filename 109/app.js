var express = require( 'express' );
var app = express();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var connectCounter = 0;

io.on( 'connection', function( socket ) {
	connectCounter++;
	io.emit('connectCounter', connectCounter);
	// console.log( "Connected" );
	console.log(connectCounter);

	socket.on('disconnect', function () {
		connectCounter--;
		// console.log( "Disconnected" );
		io.emit('connectCounter', connectCounter);
		console.log(connectCounter);
    });
});

server.listen( 3000, function () {
	console.log("listen port: 3000 ..... ");
});
