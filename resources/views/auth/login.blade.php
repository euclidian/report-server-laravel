<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Master Docs') }}</title>

    <!-- Scripts -->
    <script src="{{mix('js/app.js')}}" defer></script>

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons' rel="stylesheet">

    <!-- Styles -->
    <link href="{{mix('css/app.css')}}" rel="stylesheet">
</head>

<body>
    <div id="app">
        <v-app>
            <v-content>
                <v-container fluid fill-height>
                    <v-layout align-center justify-center>
                        <v-flex xs12 sm8 md4 lg4>
                            <v-form action="login" method="POST">
                                @csrf
                                <v-card class="elevation-1 pa-3">
                                    <v-card-text>
                                        <div class="layout column align-center">
                                            <h1 class="flex my-4 primary--text">
                                                Print Server
                                            </h1>
                                        </div>

                                        <v-text-field 
                                            @if ($errors->has('email'))
                                                error-messages="<?php echo $errors->first('email') ?>"
                                            @endif
                                            append-icon="person" name="email" label="Email" type="text"></v-text-field>
                                        <v-text-field
                                            @if ($errors->has('password'))
                                                error-messages="<?php echo $errors->first('password') ?>"
                                            @endif
                                        append-icon="lock" name="password" label="Password" id="password" type="password"></v-text-field>

                                    </v-card-text>
                                    <div class="login-btn">
                                        <v-spacer></v-spacer>
                                        <v-btn block color="primary" type="submit">Login</v-btn>
                                    </div>
                                </v-card>
                            </v-form>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-content>
        </v-app>
    </div>
</body>

</html>