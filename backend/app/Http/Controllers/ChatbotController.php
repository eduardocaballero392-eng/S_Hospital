<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function responder(Request $request)
    {
        $mensaje = $request->input('mensaje');
        $apiKey = env('GROQ_API_KEY');

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.1-8b-instant',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Eres un asistente médico virtual del Hospital System. 
                        Respondes preguntas médicas generales de forma clara y sencilla. 
                        También ayudas con preguntas sobre el sistema como cómo agendar citas, 
                        ver diagnósticos, recetas y resultados. 
                        Siempre recomienda consultar con un médico para diagnósticos específicos.
                        Responde siempre en español y de forma corta y clara.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $mensaje
                    ]
                ],
                'max_tokens' => 500,
            ]);

            $data = $response->json();
            Log::info('Groq response:', $data);

            $respuesta = $data['choices'][0]['message']['content']
                         ?? 'Lo siento, no pude procesar tu pregunta.';

            return response()->json(['respuesta' => $respuesta]);

        } catch (\Exception $e) {
            Log::error('Chatbot error: ' . $e->getMessage());
            return response()->json(['respuesta' => 'Lo siento, ocurrió un error.']);
        }
    }
}