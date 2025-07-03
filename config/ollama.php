<?php

return [
    'url' => env('OLLAMA_URL', 'http://localhost:11434'),
    'model' => env('OLLAMA_MODEL', 'llama3.2:latest'),
    'timeout' => env('OLLAMA_TIMEOUT', 180),
];
