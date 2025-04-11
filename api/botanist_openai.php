<?php
// Disable error display in the output
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Set headers to allow JSON response
header('Content-Type: application/json');

// Initialize response
$response = [
    'success' => false,
    'message' => '',
    'reply' => '',
    'debug' => [] // For debugging purposes
];

// Function to get fallback response
function getFallbackResponse($message) {
    $user_message = strtolower($message);
    
    // Simple fallback responses
    $fallback_responses = [
        'hello' => "Hello! I'm PlantGuru, your plant assistant. How can I help you with your plants today?",
        'hi' => "Hi there! I'm PlantGuru. What plant questions do you have?",
        'help' => "I can help with plant care, identification, and gardening tips. What would you like to know?",
        'water' => "Most plants need to be watered when the top inch of soil feels dry. Overwatering is a common cause of plant death, so it's better to underwater than overwater. Different plants have different water needs - succulents need less water, while tropical plants often need more.",
        'light' => "Plants have different light requirements. Some need direct sunlight, others prefer indirect light, and some can thrive in low light conditions. Check the specific needs of your plant species.",
        'fertilizer' => "Most houseplants benefit from fertilizer during their growing season (spring and summer). Use a balanced, water-soluble fertilizer at half the recommended strength to avoid burning the roots.",
        'succulent' => "Succulents need well-draining soil, plenty of light, and infrequent watering. Let the soil dry completely between waterings. They're perfect for beginners!",
        'yellow' => "Yellow leaves can indicate overwatering, underwatering, too much light, or nutrient deficiencies. Check the soil moisture and light conditions first.",
        'beginner' => "Great plants for beginners include snake plants, pothos, ZZ plants, spider plants, and peace lilies. These plants are forgiving and can tolerate a range of conditions.",
        'care' => "Basic plant care includes proper watering, adequate light, appropriate soil, occasional fertilizing, and monitoring for pests. Each plant species has specific care requirements.",
        'soil' => "Different plants need different soil types. Most houseplants do well in a well-draining potting mix. Succulents need sandy, fast-draining soil, while tropical plants prefer soil that retains more moisture.",
        'humidity' => "Many tropical houseplants prefer higher humidity. You can increase humidity by misting plants, using a humidifier, placing plants on pebble trays with water, or grouping plants together.",
        'temperature' => "Most houseplants prefer temperatures between 65-75°F (18-24°C). Avoid placing plants near drafty windows, doors, or heating/cooling vents which can cause temperature fluctuations.",
        'pruning' => "Regular pruning helps maintain plant shape, encourages bushier growth, and removes dead or diseased parts. Always use clean, sharp scissors or pruning shears."
    ];
    
    // Default fallback response
    $fallback_reply = "As a plant expert, I can help with questions about plant care, identification, or gardening tips. What specific plant information are you looking for?";
    
    // Check for keywords in the user message
    foreach ($fallback_responses as $keyword => $reply) {
        if (strpos($user_message, $keyword) !== false) {
            return $reply;
        }
    }
    
    return $fallback_reply;
}

try {
    // Check if this is a POST request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Only POST requests are allowed");
    }
    
    // Get the JSON data from the request
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);
    
    // Add to debug
    $response['debug']['received_data'] = $data;
    
    // Check if the message is provided
    if (!isset($data['message']) || empty($data['message'])) {
        throw new Exception("Message is required");
    }
    
    $user_message = $data['message'];
    
    // Create the system message for plant expertise
    $system_message = "You are a helpful botanist assistant named PlantGuru. You specialize in plant care, identification, gardening tips, and all things related to plants. Keep your answers focused on plant-related topics. If asked about non-plant topics, politely redirect the conversation to plants. Provide practical, accurate advice about plant care, identification, and gardening. Your responses should be friendly, informative, and concise (under 150 words unless detailed plant care is requested).";
    
    // Prepare the request to OpenAI API
    $openai_data = [
        'model' => 'gpt-4o-mini',
        'messages' => [
            [
                'role' => 'system',
                'content' => $system_message
            ],
            [
                'role' => 'user',
                'content' => $user_message
            ]
        ]
    ];
    
    // Add to debug
    $response['debug']['openai_data'] = $openai_data;
    
    // Initialize cURL session
    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    
    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($openai_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer Your Key'
    ]);
    
    // Set timeout
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    // Execute cURL request
    $result = curl_exec($ch);
    
    // Add to debug
    $response['debug']['curl_result'] = $result;
    
    // Check for cURL errors
    if (curl_errno($ch)) {
        $response['debug']['curl_error'] = curl_error($ch);
        $response['debug']['curl_errno'] = curl_errno($ch);
        throw new Exception("cURL Error: " . curl_error($ch));
    }
    
    // Get HTTP status code
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $response['debug']['http_code'] = $http_code;
    
    // Close cURL session
    curl_close($ch);
    
    // Decode the response
    $api_response = json_decode($result, true);
    $response['debug']['api_response'] = $api_response;
    
    // Check if there's an error in the API response
    if (isset($api_response['error'])) {
        $error_type = $api_response['error']['type'] ?? '';
        $error_message = $api_response['error']['message'] ?? 'Unknown API error';
        
        // Log the error for debugging
        $response['debug']['error_type'] = $error_type;
        $response['debug']['error_message'] = $error_message;
        
        // Handle quota exceeded error specifically
        if ($error_type === 'insufficient_quota') {
            throw new Exception("API quota exceeded. Using fallback response.");
        } else {
            throw new Exception("API Error: " . $error_message);
        }
    }
    
    // Check if the response is valid
    if (!isset($api_response['choices'][0]['message']['content'])) {
        throw new Exception("Invalid response from OpenAI API");
    }
    
    // Get the bot's reply
    $bot_reply = $api_response['choices'][0]['message']['content'];
    
    // Set success response
    $response['success'] = true;
    $response['reply'] = $bot_reply;
    
} catch (Exception $e) {
    $response['message'] = "Error: " . $e->getMessage();
    
    // Use fallback responses
    $fallback_reply = getFallbackResponse($data['message'] ?? '');
    
    // Set fallback response
    $response['success'] = true; // Set to true so the UI shows the response
    $response['reply'] = $fallback_reply;
    $response['is_fallback'] = true;
}

// Return JSON response
echo json_encode($response);
?>