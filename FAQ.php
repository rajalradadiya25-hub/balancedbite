<?php
error_reporting(0);
require('db/dbconnect.php');
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ Page</title>
    <?php require("component/Designlinks.php"); ?>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .faq-section {
            padding: 60px 0;
        }

        .faq-title {
            text-align: center;
            margin-bottom: 40px;
        }

        .faq-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
        }

        .search-bar .input-group {
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }

        .faq .card {
            margin-bottom: 20px;
        }

        .faq .card-header {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
        }

        .faq .card-body {
            border-top: 1px solid #ddd;
        }

        .contact-section {
            text-align: center;
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <?php require("component/nav.php"); ?>
    <div class="container faq-section">
        <div class="faq-title">
            <h2 style="color: rgb(0,128,0);">Frequently Asked Questions</h2>
        </div>
        <div class="search-bar">
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="searchInput" placeholder="Search FAQs">
                <button class="btn btn-success" type="button" id="searchButton">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>


        
    <div id="faqCategories"></div>
</div>

<script>
const faqData = [
    {category: "Diet", question: "What are gluten-free tips?", answer: "Read labels carefully, opt for naturally gluten-free foods like fruits and vegetables, and experiment with gluten-free grains like quinoa, rice, and millet."},
    {category: "Diet", question: "How can I follow a vegan diet and get enough protein?", answer: "Include a variety of plant-based protein sources such as beans, lentils, tofu, tempeh, and quinoa. Nuts and seeds are also excellent protein-rich options."},
    {category: "Supplements", question: "Do I need supplements if I eat a balanced diet?", answer: "Generally, if you eat a varied and balanced diet, you may not need supplements. However, certain conditions or dietary restrictions may require supplementation. Consult with a healthcare provider."},
    {category: "Supplements", question: "What are the benefits of omega-3 fatty acids?", answer: "Omega-3 fatty acids support heart health, brain function, and reduce inflammation. They are found in fatty fish, flaxseeds, and walnuts."},
    {category: "Health", question: "How does diet affect mental health?", answer: "A balanced diet supports brain function, reduces inflammation, and provides essential nutrients. Nutrient deficiencies can impact mood and cognitive function."},
    {category: "Common Concerns", question: "How do I overcome cravings while on a diet?", answer: "Identify triggers, plan healthy snacks, stay hydrated, get enough sleep, and practice mindful eating. Allow occasional treats in moderation."},
    {category: "Support", question: "Where can I find a nutritionist or dietitian?", answer: "You can find a nutritionist or dietitian through referrals from your primary care provider, local health clinics, or professional organizations like the Academy of Nutrition and Dietetics."}
];

// Group FAQs by category
const categories = [...new Set(faqData.map(f => f.category))];

function renderFAQs(filter="") {
    const container = document.getElementById("faqCategories");
    container.innerHTML = "";

    categories.forEach(cat => {
        const catFAQs = faqData.filter(f => f.category === cat && (f.question.toLowerCase().includes(filter.toLowerCase()) || f.answer.toLowerCase().includes(filter.toLowerCase())));
        if(catFAQs.length === 0) return;

        let html = `<h3 class="mt-5">${cat}</h3>
        <div class="accordion" id="${cat}FAQ">`;

        catFAQs.forEach((f, i) => {
            html += `
                <div class="card mt-2 border">
                    <div class="card-header" id="${cat}Heading${i}">
                        <h5 class="mb-0">
                            <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#${cat}Collapse${i}" aria-expanded="false" aria-controls="${cat}Collapse${i}">
                                ${f.question}
                            </button>
                        </h5>
                    </div>
                    <div id="${cat}Collapse${i}" class="collapse" aria-labelledby="${cat}Heading${i}" data-bs-parent="#${cat}FAQ">
                        <div class="card-body">${f.answer}</div>
                    </div>
                </div>
            `;
        });

        html += `</div>`;
        container.innerHTML += html;
    });
}

// Initial render
renderFAQs();

// Search functionality
document.getElementById("searchInput").addEventListener("input", e => renderFAQs(e.target.value));
</script>

        <div class="contact-section">
            <p>Can't find what you're looking for? <a href="contact-us.php">Contact us</a></p>
        </div>
    </div>
    

    <script>
        $(document).ready(function() {
            $("#searchButton").on("click", function() {
                var value = $("#searchInput").val().toLowerCase();
                $("#faqCategories .card").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>

    <?php require("component/Footer.php"); ?>
</body>

</html>