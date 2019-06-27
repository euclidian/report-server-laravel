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
        <v-flex xs4 offset-xs4 style="padding:200px 0">
            <v-card>
                <v-card-title primary-title>
                    <div>
                        <h3 class="headline mb-0">Print Server</h3>
                    </div>
                </v-card-title>
                <v-form action="login" method="POST">
                    @csrf
                    <v-container>
                        <v-flex xs10 offset-xs1>
                        <v-layout>
                            <v-text-field
                            @if ($errors->has('email'))
                                error-messages="<?php echo $errors->first('email')?>"
                            @endif
                            name="email" label="Email" required></v-text-field>
                        </v-layout>
                        <v-layout>
                            <v-text-field
                            @if ($errors->has('password'))
                                error-messages="<?php echo $errors->first('password')?>"
                            @endif
                             type="password" name="password" label="Password" required></v-text-field>
                        </v-layout>
                        <v-btn color="info" type="submit">Login</v-btn>
                        </v-flex>
                    </v-container>
                </v-form>
            </v-card>
        </v-flex>
    </v-app>
    </div>
</body>
</html>
