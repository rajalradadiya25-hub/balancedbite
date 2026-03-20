const chatBody = document.querySelector(".chat-body");
const messageInput = document.querySelector(".message-input");
const sendMessage = document.querySelector("#send-message");
const categorySelect = document.querySelector("#category-select");

const API_URL = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=AIzaSyBqXmdBMI2TBKXgPm_R9pP94XHnZZKoUmM";
const SAVE_CHAT_URL = "admin_save_faq.php";

// Hide send button initially
sendMessage.style.display = "none";
messageInput.addEventListener("input", () => {
  sendMessage.style.display = messageInput.value.trim() ? "inline-block" : "none";
});

const chatHistory = [];
const initialInputHeight = messageInput.scrollHeight;

// Greeting Keywords (Conversations not stored)
const greetingKeywords = [
  "hello", "hi", "hey", "good morning", "good afternoon", "good evening",
  "how are you", "what's up", "how's your day", "nice to meet you",
  "howdy", "greetings", "yo", "sup", "hiya", "hola", "bonjour", "namaste",
  "good to see you", "long time no see", "what’s new", "how’s everything",
  "how’s life", "how’s it going", "how have you been", "pleasure to meet you"
];
// Define Allowed Keywords for Each Category
const allowedKeywords = {
  "Common Concerns": [
    "allergy", "safety", "side effects", "ingredients", "warnings", "toxins",  
    "additives", "overdose", "labels", "expiration", "sugar", "fat",  
    "salt", "preservatives", "chemicals"
  ],
  "Diet Plans": [
    "keto", "vegan", "low carb", "high protein", "simple meals", "paleo",  
    "mediterranean", "fasting", "plant-based", "weight loss", "muscle gain",  
    "balanced diet", "low fat", "low sugar", "high fiber","diet"
  ],
  "General Nutrition": [
    "vitamins", "minerals", "calories", "nutrients", "fiber", "protein",  
    "carbs", "fats", "sugar", "hydration", "energy", "superfoods",  
    "metabolism", "digestion", "antioxidants"
  ],
  "Health and Wellness": [
    "exercise", "water", "stress", "sleep", "mental health", "self-care",  
    "yoga", "walking", "running", "stretching", "meditation", "balance",  
    "relaxation", "happiness", "focus"
  ],
  "Recipes and Meal Planning": [
    "quick meals", "snacks", "easy recipes", "meal prep", "healthy",  
    "breakfast", "lunch", "dinner", "smoothies", "soups", "salads",  
    "grains", "one-pot meals", "low-calorie", "homemade"
  ],
  "Special Dietary Needs": [
    "gluten-free", "diabetes", "heart health", "low salt", "dairy-free",  
    "low sugar", "kidney health", "nut allergies", "low cholesterol",  
    "soy-free", "autoimmune", "vegetarian", "low FODMAP", "digestive health",  
    "blood pressure"
  ],
  "Supplements and Vitamins": [
    "omega-3", "vitamin D", "iron", "calcium", "probiotics",  
    "magnesium", "zinc", "B12", "herbal", "collagen", "protein powder",  
    "multivitamins", "creatine", "turmeric", "electrolytes"
  ],
  "Support and Resources": [
    "coaching", "apps", "tools", "meal plans", "fitness",  
    "dietitian", "workshops", "ebooks", "support groups",  
    "exercise plans", "tracking", "motivation", "challenges",  
    "recipes", "videos"
  ]
};

// Function to Check if Question is a Greeting
const isGreeting = (question) => {
  return greetingKeywords.some(greet => question.toLowerCase().includes(greet));
};

// Function to Validate Question Against Allowed Keywords
const isValidQuestion = (category, question) => {
  if (!category || !allowedKeywords[category]) return false;

  return allowedKeywords[category].some(keyword => question.toLowerCase().includes(keyword));
};
// const isValidQuestion = (category, question) => {
//   return question.trim().length > 0; // Ensure the question is not empty
// };


// Create Chat Message
const createMessageElement = (content, ...classes) => {
  const div = document.createElement("div");
  div.classList.add("message", classes);
  div.innerHTML = content;
  return div;
};

// Save Chat to Database (Only for category-based questions)
const saveChatToDatabase = async (category, question, answer) => {
  if (!category) return;

  const formData = new FormData();
  formData.append("category", category);
  formData.append("question", question);
  formData.append("answer", answer);

  try {
    await fetch(SAVE_CHAT_URL, {
      method: "POST",
      body: formData,
    });
  } catch (error) {
    console.error("Error saving chat:", error);
  }
};

// Generate Bot Response
const generateBotResponse = async (incomingMessageDiv, category, question) => {
  const messageElement = incomingMessageDiv.querySelector(".message-text");

  // If question is a greeting, respond with a friendly message
  if (isGreeting(question)) {
    const greetingResponses = [
      "Hello! How can I assist you today? 😊",
      "Hey there! Hope you're having a great day! 🌟",
      "Hi! What can I do for you today? 👋",
      "Good to see you! Let me know if you have any questions! 🤖",
      "Howdy! What brings you here today? 🤠",
      "Greetings! How can I make your day better? 😃",
      "Yo! Need any help? 😎",
      "Hola! Tell me what’s on your mind. 🌍",
      "Namaste! How can I support you today? 🙏",
      "Bonjour! Let’s chat, how can I help? 🇫🇷",
      "Hi there! What can I do for you today? 💡",
      "Nice to see you! What’s up? ☀️",
      "Hope you’re having an amazing day! How can I help? ✨",
      "Welcome! Ask me anything, I’m here to help. 🤗",
      "Hey! What’s on your mind today? 💭",
      "Good day! Ready to chat? ☕",
      "Happy to see you here! Let’s get started. 🚀"
    ];
    const response = greetingResponses[Math.floor(Math.random() * greetingResponses.length)];
    messageElement.innerText = response;
    return;
  }

  // If question is invalid for selected category
  if (!isValidQuestion(category, question)) {
    messageElement.innerHTML = `<div class="error-message">Invalid question. Please ask something relevant to the category.</div>`;
    messageElement.style.color = "red";
    return;
  }

  chatHistory.push({ role: "user", parts: [{ text: question }] });

  try {
    // Simulate API response delay
    setTimeout(() => {
      messageElement.innerHTML = '<div class="thinking-indicator"><div class="dot"></div><div class="dot"></div><div class="dot"></div></div>';
    }, 500);

    const response = await fetch(API_URL, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ contents: chatHistory }),
    });

    const data = await response.json();
    const answer = data.candidates[0].content.parts[0].text.trim();

    messageElement.innerText = answer;
    chatHistory.push({ role: "model", parts: [{ text: answer }] });

    saveChatToDatabase(category, question, answer);
  } catch (error) {
    messageElement.innerText = `<div class="error-message">Error generating response.</div>`;
    messageElement.style.color = "red";
  } finally {
    incomingMessageDiv.classList.remove("thinking");
    chatBody.scrollTo({ top: chatBody.scrollHeight, behavior: "smooth" });
  }
};

// Handle User Message
const handleOutgoingMessage = (e) => {
  e.preventDefault();
  const category = categorySelect.value;
  const message = messageInput.value.trim();
  if (!message) return;

  messageInput.value = "";
  messageInput.dispatchEvent(new Event("input"));

  const outgoingMessageDiv = createMessageElement(`<div class="message-text">${message}</div>`, "user-message");
  chatBody.appendChild(outgoingMessageDiv);

  setTimeout(() => {
    const incomingMessageDiv = createMessageElement(
      `<div class="message-text"><div class="thinking-indicator"><div class="dot"></div><div class="dot"></div><div class="dot"></div></div></div>`,
      "bot-message",
      "thinking"
    );
    chatBody.appendChild(incomingMessageDiv);
    generateBotResponse(incomingMessageDiv, category, message);
  }, 600);
};

// Event Listener for Send Button
sendMessage.addEventListener("click", handleOutgoingMessage);

// Event Listener for Enter Key in Input Field
messageInput.addEventListener("keypress", (e) => {
  if (e.key === "Enter" && !e.shiftKey) {  // Prevent sending if Shift+Enter is used (for multi-line input)
    e.preventDefault(); // Prevent newline in input field
    handleOutgoingMessage(e);
  }
});
