// ============================================
// GLOBAL VARIABLES
// ============================================
// These variables need to be accessible by all functions

let receiverId = null;        // Stores the ID of the user we're chatting with
let messageInterval = null;   // Stores the auto-refresh timer


// ============================================
// FUNCTION 1: openChat()
// ============================================
// PURPOSE: Opens a chat with a specific user
// CALLED BY: onclick in chatroom.php (line 45)
// PARAMETERS: 
//   - id: The user ID we want to chat with (from database)
//   - username: The username to display
// PHP FILES USED: None directly, but sets up for get_messages.php

function openChat(id, username) {
    console.log("Opening chat with user ID:", id, "Username:", username);
    
    // Store the receiver ID globally so other functions can use it
    receiverId = id;
    
    // Clear any existing auto-refresh timer to prevent multiple timers running
    if (messageInterval) {
        clearInterval(messageInterval);
        messageInterval = null;
    }
    
    // Clear the chat immediately to remove placeholder messages
    const chatContainer = document.querySelector(".chat");
    if (chatContainer) {
        chatContainer.innerHTML = '<li style="text-align:center; padding: 20px;">Loading messages...</li>';
    }
    
    // Load messages immediately when chat opens
    loadMessages();
    
    // Set up auto-refresh: reload messages every 3 seconds
    // This keeps the chat updated with new messages automatically
    messageInterval = setInterval(loadMessages, 3000);
    
    console.log("Chat opened successfully");
}


// ============================================
// FUNCTION 2: loadMessages()
// ============================================
// PURPOSE: Fetch and display messages between current user and selected user
// CALLED BY: openChat() and auto-refresh interval
// PHP FILE USED: includes/get_messages.php
// 
// HOW IT WORKS:
// 1. Sends GET request to get_messages.php with receiver_id parameter
// 2. get_messages.php queries database for all messages between two users
// 3. Returns JSON array of message objects
// 4. JavaScript displays each message in the chat window

function loadMessages() {
    // Don't load if no user is selected
    if (!receiverId) {
        console.log("No receiver selected, skipping message load");
        return;
    }
    
    console.log("Loading messages for receiver ID:", receiverId);
    
    // Make HTTP GET request to includes/get_messages.php
    // This calls: includes/get_messages.php?receiver_id=X
    fetch("includes/get_messages.php?receiver_id=" + receiverId)
        .then(res => {
            // Convert response to text first to debug any errors
            return res.text().then(text => {
                console.log("Raw response from server:", text);
                
                try {
                    // Try to parse as JSON
                    return JSON.parse(text);
                } catch (e) {
                    // If parsing fails, show error and return empty array
                    console.error("JSON parse error:", e);
                    console.error("Response was:", text);
                    return [];
                }
            });
        })
        .then(messages => {
            console.log("Parsed messages:", messages);
            
            // Find the chat container in the HTML
            const chatContainer = document.querySelector(".chat");
            if (!chatContainer) {
                console.error("Chat container not found!");
                return;
            }
            
            // Clear existing messages
            chatContainer.innerHTML = "";
            
            // Loop through each message and create HTML
            messages.forEach(msg => {
                console.log("Processing message:", msg);
                
                // Determine if message is from them (left) or us (right)
                // If sender_id matches receiverId, message is from them (left side)
                // If receiver_id matches receiverId, message is from us (right side)
                const isFromThem = msg.sender_id == receiverId;
                const liClass = isFromThem ? "left clearfix" : "right clearfix";
                const pullClass = isFromThem ? "pull-left" : "pull-right";
                const imgSrc = isFromThem 
                    ? "https://bootdey.com/img/Content/user_3.jpg" 
                    : "https://bootdey.com/img/Content/user_1.jpg";
                const senderName = isFromThem ? "Them" : "You";
                
                console.log("Message from:", senderName, "| isFromThem:", isFromThem);
                
                // Create the message HTML
                const li = document.createElement("li");
                li.className = liClass;
                
                li.innerHTML = `
                    <span class="chat-img ${pullClass}">
                        <img src="${imgSrc}" alt="User Avatar">
                    </span>
                    <div class="chat-body clearfix">
                        <div class="header">
                            <strong class="primary-font">${senderName}</strong>
                            <small class="pull-right text-muted">
                                <i class="fa fa-clock-o"></i> Just now
                            </small>
                        </div>
                        <p>${escapeHtml(msg.message)}</p>
                    </div>
                `;
                
                // Add to chat container
                chatContainer.appendChild(li);
                console.log("Message HTML added to chat");
            });
            
            // Debug: Check what's in the chat container
            console.log("Total messages in chat:", chatContainer.children.length);
            console.log("Chat HTML:", chatContainer.innerHTML);
            
            // Scroll to bottom to show latest messages
            const chatMessage = document.querySelector(".chat-message");
            if (chatMessage) {
                chatMessage.scrollTop = chatMessage.scrollHeight;
                console.log("Scrolled to bottom");
            } else {
                console.error("chat-message container not found!");
            }
            
            console.log("Messages loaded successfully");
        })
        .catch(err => {
            console.error("Error loading messages:", err);
        });
}


// ============================================
// FUNCTION 3: escapeHtml()
// ============================================
// PURPOSE: Prevent XSS attacks by escaping HTML characters
// CALLED BY: loadMessages()
// 
// SECURITY: This prevents malicious users from injecting JavaScript
// Example: converts <script>alert('hack')</script> to safe text

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}


// ============================================
// FUNCTION 4: sendMessage()
// ============================================
// PURPOSE: Send a new message to the selected user
// CALLED BY: Send button click and Enter key press
// PHP FILE USED: includes/send_message.php
// 
// HOW IT WORKS:
// 1. Gets message text from input field
// 2. Sends POST request to send_message.php with message data
// 3. send_message.php inserts message into database
// 4. Returns success/error status
// 5. JavaScript clears input and reloads messages to show new message

function sendMessage() {
    console.log("Attempting to send message...");
    
    // Find the input field
    const input = document.querySelector("#chatInput input");
    
    // Validate: must have receiver selected and non-empty message
    if (!receiverId) {
        console.error("No receiver selected");
        return;
    }
    
    if (!input || input.value.trim() === "") {
        console.error("Message is empty");
        return;
    }
    
    console.log("Sending message:", input.value);
    
    // Create FormData object to send POST data
    // This is like a form submission
    const formData = new FormData();
    formData.append('message', input.value);
    
    // Make HTTP POST request to includes/send_message.php
    // The receiver_id is in the URL as GET parameter
    // The message is in the body as POST parameter
    fetch("includes/send_message.php?receiver_id=" + receiverId, {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        console.log("Send message response:", data);
        
        // Check if successful (note: your PHP has typo 'sucess')
        if (data.status === 'success' || data.status === 'sucess') {
            console.log("Message sent successfully");
            
            // Clear the input field
            input.value = "";
            
            // Reload messages to show the new message
            loadMessages();
        } else {
            console.error("Failed to send message:", data);
        }
    })
    .catch(err => {
        console.error("Error sending message:", err);
    });
}


// ============================================
// FUNCTION 5: Initialize Event Listeners
// ============================================
// PURPOSE: Set up button clicks and keyboard events when page loads
// CALLED BY: Browser when DOM is ready
// 
// This runs once when the page loads to attach event listeners

document.addEventListener("DOMContentLoaded", function() {
    console.log("Page loaded, setting up event listeners...");
    
    // Find the Send button
    const sendBtn = document.querySelector("#chatInput button");
    if (sendBtn) {
        // Attach click event to Send button
        sendBtn.onclick = sendMessage;
        console.log("Send button event attached");
    } else {
        console.error("Send button not found!");
    }
    
    // Find the input field
    const input = document.querySelector("#chatInput input");
    if (input) {
        // Allow Enter key to send message
        input.addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                console.log("Enter key pressed");
                sendMessage();
            }
        });
        console.log("Input field Enter key event attached");
    } else {
        console.error("Input field not found!");
    }
    
    console.log("Event listeners setup complete");
});