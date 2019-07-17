<?php

return [
    "group" 	 	=> env('TOKEN_GROUP', "PrintAPI"),
    "url" 		 	=> env('TOKEN_URL'),
    "upload_url" 	=> env('TOKEN_UPLOAD_URL'),
    "download_url" 	=> env('TOKEN_DOWNLOAD_URL'),
    "grant_type" 	=> env('TOKEN_GRANT_TYPE'),
    "client_id"	 	=> env('TOKEN_CLIENT_ID'),
    "client_secret"	=> env('TOKEN_CLIENT_SECRET'),
    "username"		=> env('TOKEN_USERNAME'),
    "password"		=> env('TOKEN_PASSWORD')
];
