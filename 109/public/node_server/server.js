var socket = require( 'socket.io' );
var express = require( 'express' );
var http = require( 'http' );

var app = express();
var server = http.createServer( app );
server.listen( 3000 );

var io = socket.listen( server );
process.env.NODE_TLS_REJECT_UNAUTHORIZED = 0;

io.sockets.on( 'connection', function( client ) {
	console.log( "New client !" );
	
	client.on( 'message', function( data ) {
		console.log( 'Message received ' + data.name + ":" + data.message );
		
		//client.broadcast.emit( 'message', { name: data.name, message: data.message } );
		io.sockets.emit( 'message', { name: data.name, message: data.message } );

        nodemailer.createTestAccount((err, account) => {
            // create reusable transporter object using the default SMTP transport
            let transporter = nodemailer.createTransport({
                host: '103.18.6.212',
                port: 25,
                secure: false, // true for 465, false for other ports
                rejectUnauthorized:false,
                auth: {
                    user: 'no-reply@linh123.com', // generated ethereal user
                    pass: 'f614tZyG-&dg' // generated ethereal password
                }
            });

            /*let transporter = nodemailer.createTransport({
                host: 'smtp.gmail.com',
                port: 587,
                secure: false, // true for 465, false for other ports
                rejectUnauthorized:false,
                auth: {
                    user: 'ngokmt1@gmail.com', // generated ethereal user
                    pass: 'Congngono1_!@' // generated ethereal password
                }
            });*/

            // setup email data with unicode symbols
            let mailOptions = {
                from: '"Cong Ngo 👻" <no-reply@linh123.com>', // sender address
                to: 'congngotn@gmail.com', // list of receivers
                subject: 'New message from '+ data.name +'', // Subject line
                text: 'Hello world?', // plain text body
                html: '<b>'+ data.message +'</b>' // html body
            };

            // send mail with defined transport object
            transporter.sendMail(mailOptions, (error, info) => {
                if (error) {
                    return console.log(error);
                }
                console.log('Message sent: %s', info.messageId);
                // Preview only available when sending through an Ethereal account
                console.log('Preview URL: %s', nodemailer.getTestMessageUrl(info));

                // Message sent: <b658f8ca-6296-ccf4-8306-87d57a0b4321@example.com>
                // Preview URL: https://ethereal.email/message/WaQKMgKddxQDoou...
            });
        });
	});
});

'use strict';
const nodemailer = require('nodemailer');

// Generate test SMTP service account from ethereal.email
// Only needed if you don't have a real mail account for testing
