<?php

return [
    "group" 	 	=> env('TOKEN_GROUP', "PrintAPI"),
    "url" 		 	=> env('TOKEN_URL', "https://media-stg.asmatende.asmat.app/oauth/token"),
    "upload_url" 	=> env('TOKEN_UPLOAD_URL', "https://media-stg.asmatende.asmat.app/api/image/upload"),
    "download_url" 	=> env('TOKEN_DOWNLOAD_URL', "https://media-stg.asmatende.asmat.app"),
    "grant_type" 	=> env('TOKEN_GRANT_TYPE', "client_credentials"),
    "client_id"	 	=> env('TOKEN_CLIENT_ID'),
    "client_secret"	=> env('TOKEN_CLIENT_SECRET'),
    "username"		=> env('TOKEN_USERNAME'),
    "password"		=> env('TOKEN_PASSWORD')
];
