document.addEventListener("DOMContentLoaded", () => {
    // DOM Elements
    const chatMessages = document.getElementById("chatMessages")
    const messageInput = document.getElementById("messageInput")
    const sendButton = document.getElementById("sendMessageBtn")
    const botanistModal = document.getElementById("botanistModal")
    const closeBotanistModal = document.getElementById("closeBotanistModal")
    const askBotanistBtn = document.getElementById("askBotanistBtn")
  
    // Chat history
    const chatHistory = []
  
    // API endpoint - using the new OpenAI implementation
    const API_ENDPOINT = "../api/botanist_openai.php"
  
    // Initialize chat
    function initChat() {
      // Clear chat messages
      chatMessages.innerHTML = ""
  
      // Add welcome message
      const welcomeHTML = `
              <div class="welcome-message">
                  <i class="fas fa-leaf"></i>
                  <h3>Welcome to PlantGuru!</h3>
                  <p>I'm your personal botanist assistant. Ask me anything about plants, gardening, or plant care!</p>
                  <div class="suggestion-chips">
                      <div class="suggestion-chip">How do I care for succulents?</div>
                      <div class="suggestion-chip">What plants are safe for pets?</div>
                      <div class="suggestion-chip">How often should I water my plants?</div>
                      <div class="suggestion-chip">Best plants for beginners?</div>
                  </div>
              </div>
          `
  
      chatMessages.innerHTML = welcomeHTML
  
      // Add event listeners to suggestion chips
      document.querySelectorAll(".suggestion-chip").forEach((chip) => {
        chip.addEventListener("click", function () {
          messageInput.value = this.textContent
          sendMessage()
        })
      })
    }
  
    // Format timestamp
    function formatTime() {
      const now = new Date()
      return now.getHours().toString().padStart(2, "0") + ":" + now.getMinutes().toString().padStart(2, "0")
    }
  
    // Add message to chat
    function addMessage(content, isUser = false) {
      // Remove welcome message if it exists
      const welcomeMessage = document.querySelector(".welcome-message")
      if (welcomeMessage) {
        welcomeMessage.remove()
      }
  
      const messageElement = document.createElement("div")
      messageElement.className = isUser ? "message message-user" : "message message-bot"
  
      // Format message content with line breaks
      const formattedContent = content.replace(/\n/g, "<br>")
  
      messageElement.innerHTML = `
              ${formattedContent}
              <span class="message-time">${formatTime()}</span>
          `
  
      chatMessages.appendChild(messageElement)
  
      // Scroll to bottom
      chatMessages.scrollTop = chatMessages.scrollHeight
  
      // Add to chat history
      chatHistory.push({
        role: isUser ? "user" : "bot",
        content: content,
        time: formatTime(),
      })
    }
  
    // Show typing indicator
    function showTypingIndicator() {
      const typingElement = document.createElement("div")
      typingElement.className = "typing-indicator"
      typingElement.id = "typingIndicator"
      typingElement.innerHTML = `
              <div class="typing-dot"></div>
              <div class="typing-dot"></div>
              <div class="typing-dot"></div>
          `
  
      chatMessages.appendChild(typingElement)
      chatMessages.scrollTop = chatMessages.scrollHeight
    }
  
    // Hide typing indicator
    function hideTypingIndicator() {
      const typingElement = document.getElementById("typingIndicator")
      if (typingElement) {
        typingElement.remove()
      }
    }
  
    // Send message to API
    async function sendMessage() {
      const message = messageInput.value.trim()
  
      if (!message) return
  
      // Add user message to chat
      addMessage(message, true)
  
      // Clear input
      messageInput.value = ""
  
      // Disable send button
      sendButton.disabled = true
  
      // Show typing indicator
      showTypingIndicator()
  
      try {
        // Try the API
        const response = await fetch(API_ENDPOINT, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            message: message,
          }),
        })
  
        const data = await response.json()
  
        // Hide typing indicator
        hideTypingIndicator()
  
        // Always use the reply if it exists, regardless of whether it's a fallback or not
        if (data.reply) {
          // Add bot response to chat
          addMessage(data.reply)
        } else {
          // Only if there's no reply at all
          console.error("API Error: No reply in response", data)
          addMessage("I'm having trouble connecting to my knowledge base right now. Please try again in a moment.")
        }
      } catch (error) {
        console.error("API Error:", error)
  
        // Hide typing indicator
        hideTypingIndicator()
  
        // Show error message
        addMessage("I'm having trouble connecting to my knowledge base right now. Please try again in a moment.")
      }
  
      // Enable send button
      sendButton.disabled = false
    }
  
    // Event listeners
    if (sendButton) {
      sendButton.addEventListener("click", sendMessage)
    }
  
    if (messageInput) {
      messageInput.addEventListener("keypress", (e) => {
        if (e.key === "Enter") {
          sendMessage()
        }
      })
    }
  
    if (askBotanistBtn) {
      askBotanistBtn.addEventListener("click", (e) => {
        e.preventDefault()
        botanistModal.classList.add("active")
        document.body.style.overflow = "hidden"
  
        // Initialize chat if it's empty
        if (chatHistory.length === 0) {
          initChat()
        }
      })
    }
  
    if (closeBotanistModal) {
      closeBotanistModal.addEventListener("click", () => {
        botanistModal.classList.remove("active")
        document.body.style.overflow = ""
      })
    }
  
    // Close modal when clicking outside
    if (botanistModal) {
      botanistModal.addEventListener("click", (e) => {
        if (e.target === botanistModal) {
          botanistModal.classList.remove("active")
          document.body.style.overflow = ""
        }
      })
    }
  
    // Initialize chat when document is loaded
    document.addEventListener("initChat", initChat)
  
    // Initialize chat if the modal is opened
    if (botanistModal && botanistModal.classList.contains("active")) {
      initChat()
    }
})  