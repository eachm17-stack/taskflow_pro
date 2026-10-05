<?php

'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_methods' => ['*'],
'allowed_origins' => ['*'], // o tu dominio de Vercel
'allowed_headers' => ['*'],
'supports_credentials' => true,