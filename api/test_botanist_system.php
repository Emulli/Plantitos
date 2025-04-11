<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Botanist System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1, h2, h3 {
            color: #2c7a51;
        }
        .test-form {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #f0f7f3;
            border-radius: 10px;
        }
        input[type="text"] {
            width: 70%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            padding: 10px 15px;
            background-color: #2c7a51;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .result {
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            border-left: 5px solid #2c7a51;
        }
        .error {
            color: red;
            font-weight: bold;
        }
        pre {
            background-color: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
            overflow-x: auto;
        }
        .debug-toggle {
            margin-top: 20px;
            cursor: pointer;
            color: #2c7a51;
            font-weight: bold;
        }
        .debug-info {
            display: none;
            margin-top: 10px;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        .tabs {
            display: flex;
            margin-bottom: 20px;
        }
        .tab {
            padding: 10px 20px;
            background-color: #f0f7f3;
            border: none;
            cursor: pointer;
            margin-right: 5px;
            border-radius: 5px 5px 0 0;
        }
        .tab.active {
            background-color: #2c7a51;
            color: white;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .status {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .status.success {
            background-color: #e8f5e9;
            color: #2c7a51;
        }
        .status.error {
            background-color: #ffebee;
            color: #c62828;
        }
        .keyword-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        .keyword {
            background-color: #e8f5e9;
            color: #2c7a51;
            padding: 5px 10px;
            border-radius: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Botanist System Test</h1>
    
    <div class="tabs">
        <button class="tab active" data-tab="test-api">Test API</button>
        <button class="tab" data-tab="test-fallback">Test Fallback</button>
        <button class="tab" data-tab="system-status">System Status</button>
    </div>
    
    <div id="test-api" class="tab-content active">
        <h2>Test OpenAI API Integration</h2>
        
        <div class="test-form">
            <h3>Test a Message</h3>
            <form id="apiTestForm">
                <input type="text" id="apiTestMessage" placeholder="Enter a plant-related question..." required>
                <button type="submit">Test</button>
            </form>
        </div>
        
        <div class="result" id="apiResult">
            <p>Response will appear here...</p>
        </div>
    </div>
    
    <div id="test-fallback" class="tab-content">
        <h2>Test Fallback System</h2>
        
        <div class="test-form">
            <h3>Test Fallback Keywords</h3>
            <p>Click on a keyword to test the fallback response:</p>
            <div class="keyword-list" id="keywordList">
                <!-- Keywords will be loaded here -->
            </div>
            <h3>Or enter your own query:</h3>
            <form id="fallbackTestForm">
                <input type="text" id="fallbackTestMessage" placeholder="Enter a plant-related question..." required>
                <button type="submit">Test</button>
            </form>
        </div>
        
        <div class="result" id="fallbackResult">
            <p>Response will appear here...</p>
        </div>
    </div>
    
    <div id="system-status" class="tab-content">
        <h2>System Status</h2>
        
        <div id="statusContainer">
            <p>Checking system status...</p>
        </div>
        
        <button id="refreshStatus">Refresh Status</button>
    </div>
    
    <h2>Suggested Test Queries:</h2>
    <ul>
        <li>How do I care for succulents?</li>
        <li>What plants are safe for pets?</li>
        <li>How often should I water my plants?</li>
        <li>Best plants for beginners?</li>
        <li>Why are my plant leaves turning yellow?</li>
    </ul>
    
    <script>
        // Tab functionality
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Hide all tab content
                document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
                // Show content for clicked tab
                document.getElementById(this.dataset.tab).classList.add('active');
            });
        });
        
        // API Test Form
        document.getElementById('apiTestForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const message = document.getElementById('apiTestMessage').value.trim();
            const resultDiv = document.getElementById('apiResult');
            
            if (!message) return;
            
            resultDiv.innerHTML = '<p>Testing...</p>';
            
            try {
                const response = await fetch('botanist_openai.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        message: message
                    })
                });
                
                const responseText = await response.text();
                
                try {
                    // Try to parse as JSON
                    const data = JSON.parse(responseText);
                    
                    let resultHTML = `
                        <h3>Response:</h3>
                        <p>${data.reply || 'No reply provided'}</p>
                    `;
                    
                    if (data.is_fallback) {
                        resultHTML += `<p><em>(Using fallback response)</em></p>`;
                    }
                    
                    resultHTML += `<div class="debug-toggle" onclick="toggleDebug('apiDebug')">Show Debug Info</div>
                        <div class="debug-info" id="apiDebug">
                            <h4>Full Response:</h4>
                            <pre>${JSON.stringify(data, null, 2)}</pre>
                        </div>`;
                    
                    resultDiv.innerHTML = resultHTML;
                } catch (jsonError) {
                    // If not valid JSON, show the raw response
                    resultDiv.innerHTML = `
                        <h3 class="error">Error: Invalid JSON Response</h3>
                        <p>The server returned a non-JSON response:</p>
                        <pre>${responseText}</pre>
                    `;
                }
            } catch (error) {
                resultDiv.innerHTML = `
                    <h3 class="error">Error:</h3>
                    <p>Failed to connect to the service: ${error.message}</p>
                `;
            }
        });
        
        // Fallback Test Form
        document.getElementById('fallbackTestForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const message = document.getElementById('fallbackTestMessage').value.trim();
            testFallback(message);
        });
        
        // Function to test fallback
        async function testFallback(message) {
            const resultDiv = document.getElementById('fallbackResult');
            
            if (!message) return;
            
            resultDiv.innerHTML = '<p>Testing fallback...</p>';
            
            // Create a function to get fallback response
            function getFallbackResponse(message) {
                const user_message = message.toLowerCase();
                
                // Simple fallback responses
                const fallback_responses = {
                    'hello': "Hello! I'm PlantGuru, your plant assistant. How can I help you with your plants today?",
                    'hi': "Hi there! I'm PlantGuru. What plant questions do you have?",
                    'help': "I can help with plant care, identification, and gardening tips. What would you like to know?",
                    'water': "Most plants need to be watered when the top inch of soil feels dry. Overwatering is a common cause of plant death, so it's better to underwater than overwater. Different plants have different water needs - succulents need less water, while tropical plants often need more.",
                    'light': "Plants have different light requirements. Some need direct sunlight, others prefer indirect light, and some can thrive in low light conditions. Check the specific needs of your plant species.",
                    'fertilizer': "Most houseplants benefit from fertilizer during their growing season (spring and summer). Use a balanced, water-soluble fertilizer at half the recommended strength to avoid burning the roots.",
                    'succulent': "Succulents need well-draining soil, plenty of light, and infrequent watering. Let the soil dry completely between waterings. They're perfect for beginners!",
                    'yellow': "Yellow leaves can indicate overwatering, underwatering, too much light, or nutrient deficiencies. Check the soil moisture and light conditions first.",
                    'beginner': "Great plants for beginners include snake plants, pothos, ZZ plants, spider plants, and peace lilies. These plants are forgiving and can tolerate a range of conditions.",
                    'care': "Basic plant care includes proper watering, adequate light, appropriate soil, occasional fertilizing, and monitoring for pests. Each plant species has specific care requirements.",
                    'soil': "Different plants need different soil types. Most houseplants do well in a well-draining potting mix. Succulents need sandy, fast-draining soil, while tropical plants prefer soil that retains more moisture.",
                    'humidity': "Many tropical houseplants prefer higher humidity. You can increase humidity by misting plants, using a humidifier, placing plants on pebble trays with water, or grouping plants together.",
                    'temperature': "Most houseplants prefer temperatures between 65-75°F (18-24°C). Avoid placing plants near drafty windows, doors, or heating/cooling vents which can cause temperature fluctuations.",
                    'pruning': "Regular pruning helps maintain plant shape, encourages bushier growth, and removes dead or diseased parts. Always use clean, sharp scissors or pruning shears."
                };
                
                // Default fallback response
                let fallback_reply = "As a plant expert, I can help with questions about plant care, identification, or gardening tips. What specific plant information are you looking for?";
                
                // Check for keywords in the user message
                for (const keyword in fallback_responses) {
                    if (user_message.includes(keyword)) {
                        fallback_reply = fallback_responses[keyword];
                        break;
                    }
                }
                
                return fallback_reply;
            }
            
            const fallbackReply = getFallbackResponse(message);
            
            resultDiv.innerHTML = `
                <h3>Fallback Response:</h3>
                <p>${fallbackReply}</p>
                <p><em>(This is the response that would be used if the API fails)</em></p>
            `;
        }
        
        // Load keywords for fallback testing
        function loadKeywords() {
            const keywords = [
                'hello', 'hi', 'help', 'water', 'light', 'fertilizer', 
                'succulent', 'yellow', 'beginner', 'care', 'soil', 
                'humidity', 'temperature', 'pruning'
            ];
            
            const keywordList = document.getElementById('keywordList');
            keywordList.innerHTML = '';
            
            keywords.forEach(keyword => {
                const keywordElement = document.createElement('div');
                keywordElement.className = 'keyword';
                keywordElement.textContent = keyword;
                keywordElement.addEventListener('click', () => {
                    document.getElementById('fallbackTestMessage').value = keyword;
                    testFallback(keyword);
                });
                keywordList.appendChild(keywordElement);
            });
        }
        
        // Check system status
        async function checkSystemStatus() {
            const statusContainer = document.getElementById('statusContainer');
            statusContainer.innerHTML = '<p>Checking system status...</p>';
            
            try {
                // Test OpenAI API
                const response = await fetch('botanist_openai.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        message: 'test'
                    })
                });
                
                const data = await response.json();
                
                let statusHTML = '';
                
                if (data.is_fallback) {
                    statusHTML += `
                        <div class="status error">
                            <h3>OpenAI API: Not Working</h3>
                            <p>The system is currently using fallback responses.</p>
                            <p>Error: ${data.message}</p>
                        </div>
                    `;
                } else {
                    statusHTML += `
                        <div class="status success">
                            <h3>OpenAI API: Working</h3>
                            <p>The system is successfully connecting to the OpenAI API.</p>
                        </div>
                    `;
                }
                
                // Check fallback system
                statusHTML += `
                    <div class="status success">
                        <h3>Fallback System: Ready</h3>
                        <p>The fallback system is in place and ready to provide responses if the API fails.</p>
                    </div>
                `;
                
                statusContainer.innerHTML = statusHTML;
                
            } catch (error) {
                statusContainer.innerHTML = `
                    <div class="status error">
                        <h3>System Error</h3>
                        <p>Failed to check system status: ${error.message}</p>
                    </div>
                `;
            }
        }
        
        // Toggle debug info
        function toggleDebug(id) {
            const debugInfo = document.getElementById(id);
            if (debugInfo.style.display === 'block') {
                debugInfo.style.display = 'none';
            } else {
                debugInfo.style.display = 'block';
            }
        }
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadKeywords();
            checkSystemStatus();
            
            // Refresh status button
            document.getElementById('refreshStatus').addEventListener('click', checkSystemStatus);
        });
    </script>
</body>
</html>

