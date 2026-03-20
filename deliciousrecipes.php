<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

//error_reporting(0);
require('db/dbconnect.php');
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delicious Recipe Page</title>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        h1 { 
            color: #007bff; 
        }
        .chatbox {
            border: 1px solid #ccc;
            padding: 20px;
            background-color: #fff;
            width: 50%;
            margin: 0 auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .chat-output {
            height: 400px;
            overflow-y: scroll;
            padding: 10px;
            background-color: #f0f0f0;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
        }
        .chat-form {
            display: flex;
            align-items: center;
        }
        .chat-form input {
            width: calc(100% - 10px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }
        .chat-form button {
            padding: 10px;
            background-color: #28a745;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }
        .chat-form button i {
            margin-right: 5px;
        }
        .chat-bubble {
            padding: 10px;
            margin: 10px 0;
            border-radius: 10px;
            max-width: 60%;
            word-wrap: break-word;
        }
        .user-bubble {
            background-color: #007bff;
            color: white;
            align-self: flex-end;
        }
        .bot-bubble {
            background-color: #e2e2e2;
            color: black;
            align-self: flex-start;
        }
    </style>
    <?php require 'component/Designlinks.php' ?>
</head>
<body>
    <?php require 'component/nav.php' ?>
    <div class="container-fluid bg-dark pt-5 pb-5">
        <h1 class="text-center text-light pb-3">Recipe Chatbot</h1>
        <div class="chatbox">
            <div id="chat-output" class="chat-output"></div>
            <form id="chat-form" class="chat-form mt-3">
                <div class="form-group" style="flex-grow: 1;">
                    <input type="text" class="form-control" id="recipe-name" placeholder="Enter recipe name" required>
                </div>
                <button type="submit" class="btn btn-lg text-light" style="background-color: rgb(0,128,0);">
                    <i class="fas fa-paper-plane"></i> 
                </button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- Font Awesome for Icons -->
    <script>
        $(document).ready(function() {
            $('#chat-form').on('submit', function(e) {
                e.preventDefault();
                var recipeName = $('#recipe-name').val();

                $('#chat-output').html('<div class="chat-bubble user-bubble"><strong>You:</strong> ' + recipeName + '</div>');

                $.ajax({
                    url: 'deliciousrecipes-chat.php',
                    method: 'POST',
                    data: { recipe_name: recipeName },
                    success: function(response) {
                        $('#chat-output').html('<div class="chat-bubble bot-bubble"><strong>Chatbot:</strong> ' + response + '</div>');
                        $('#chat-output').scrollTop($('#chat-output')[0].scrollHeight);
                    },
                    error: function() {
                        $('#chat-output').html('<div class="chat-bubble bot-bubble"><strong>Chatbot:</strong> Sorry, something went wrong.</div>');
                    }
                });

                $('#recipe-name').val('');
            });
        });
    </script>
    <?php require 'component/footer.php' ?>
</body>
</html>
