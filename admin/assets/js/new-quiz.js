
//   modal add quiz
document.addEventListener("DOMContentLoaded", function () {
  const addChoiceBtn = document.getElementById('addChoiceBtn');
  const choicesContainer = document.getElementById('choices');
  const correctChoiceSelect = document.getElementById('correctChoice');
  const addQuestionForm = document.getElementById('addQuestionForm');
  let choiceIndex = 0; // Start with Option A
  const maxChoices = 4; // Maximum number of choices

  addChoiceBtn.addEventListener('click', function () {
    if (choiceIndex < maxChoices) {
      const newChoiceInput = document.createElement('div');
      newChoiceInput.classList.add('form-group');

      const label = document.createElement('label');
      label.textContent = 'Option ' + String.fromCharCode(65 + choiceIndex); // Convert index to character (A, B, C, ...)

      const choiceInput = document.createElement('input');
      choiceInput.type = 'text';
      choiceInput.id = 'choices';
      choiceInput.classList.add('form-control', 'choice');
      choiceInput.name = 'choice' + choiceIndex; // Set name as choice0, choice1, choice2, ...
      choiceInput.placeholder = 'Enter Choice';

      newChoiceInput.appendChild(label);
      newChoiceInput.appendChild(choiceInput);

      choicesContainer.appendChild(newChoiceInput);

      // Add option to correct choice dropdown based on the new choice
      const option = document.createElement('option');
      option.value = choiceIndex; // Use the choice index as the value
      option.textContent = 'Option ' + String.fromCharCode(65 + choiceIndex);
      correctChoiceSelect.appendChild(option);

      choiceIndex++; // Increment choice index for the next option
    }
  });

  addQuestionForm.addEventListener('submit', function (event) {
    event.preventDefault();

    // Get question input value
    const question = document.getElementById('question').value.trim();
    const quizid = document.getElementById('quizid').value;

    // Get all choice inputs
    const choiceInputs = document.querySelectorAll('.choice');
    const choices = Array.from(choiceInputs).map(input => input.value.trim());

    // Get correct choice value (index)
    const correctChoiceIndex = document.getElementById('correctChoice').value;

    // Validate number of choices
    if (choices.length < 2) {
      alert("Options must be 2 or more");
      return; // Stop form submission if validation fails
    }

    // Create FormData object
    const formData = new FormData();

    // Append question to form data
    formData.append('question', question);
    formData.append('quizid', quizid);

    // Append choices as an array to form data
    choices.forEach((choice, index) => {
      formData.append(`choices[${index}]`, choice);
    });

    // Append correct choice (index) to form data
    formData.append('correctChoice', choices[correctChoiceIndex]); // Pass the actual value

    // Log form data
    console.log('Question:', question);
    console.log('Quiz ID:', quizid);
    console.log('Choices:', choices);
    console.log('Correct Answer:', choices[correctChoiceIndex]); // Log the actual value

    // Clear form inputs
    addQuestionForm.reset();

    // Reset choice index for the next question
    choiceIndex = 0;

    // Remove previously generated choice inputs
    choicesContainer.innerHTML = '';

    // Reset correct choice dropdown
    correctChoiceSelect.innerHTML = '';

    fetch('function/newquiz-function/process_quiz.php', {
      method: 'POST',
      body: formData
    })
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.text();
      })
      .then(data => {
        if (data === "errChoice") {
          console.log(data);
          alert("Options must be 2 or more");
        } else {
          alert("Question saved");
        }
      })
      .catch(error => {
        console.error('There was a problem with the fetch operation:', error);
        alert('Error: ' + error.message); // Display error message in alert
      });
  });

  // Ensure the modal state is reset on close
  $('#editQuestionModal').on('hidden.bs.modal', function () {
    $(this).find('.editmodalbody').empty(); // Clear modal content
    // Reset any other state variables if needed
  });

  // Use event delegation for the click event
  $(document).on('click', '.edit-question-button', function() {
    var id = $(this).data('id');
    $.ajax({
      url: 'function/newquiz-function/edit-temp-question.php',
      type: 'post',
      data: { id: id },
      success: function(response) {
        $('.editmodalbody').html(response);
        $('#editQuestionModal').modal('show');

        $(document).on('click', '#close-btn', function() {
          $('#editQuestionModal').modal('hide');
        });
      }
    });
  });
});


// Define the fetchQuizQuestions function outside of the DOMContentLoaded event listener



function resetElement(element) {
  const originalContent = element.getAttribute("data-original-content");
  const quizId = element.getAttribute("data-quiz-id"); // Get the quizId

  const newElement = document.createElement(element.tagName.toLowerCase());
  newElement.setAttribute("class", element.getAttribute("class"));
  newElement.textContent = originalContent;

  // Set the data-quiz-id attribute in the new element
  newElement.setAttribute("data-quiz-id", quizId);

  // Append the pencil icon span if necessary
  const pencilIcon = document.createElement("span");
  pencilIcon.setAttribute("class", "mdi mdi-pencil");
  newElement.appendChild(pencilIcon);

  element.replaceWith(newElement);
  attachEditListener(newElement);
}


// Function to send updated data to the server
function updateQuizData(element, newTitle, newDescription, quizId) {
  // Create a FormData object to send the updated data
  const formData = new FormData();
  formData.append("quizId", quizId);
  formData.append("newTitle", newTitle);
  formData.append("newDescription", newDescription);

  // Send the updated data to the server using AJAX
  fetch("function/newquiz-function/update-quiz-data.php", {
    method: "POST",
    body: formData,
    cache: "no-cache" // Prevent caching
  })
    .then(response => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.text();
    })
    .then(data => {
      console.log(data); // Output any response from the server
      // Reset the element to its initial state
      resetElement(element);
    })
    .catch(error => {
      console.error("There was a problem updating quiz data:", error);
    });
}

// Function to handle editing of elements
function handleEdit(element, isH4) {
  // Get the quizId
  const quizId = element.getAttribute("data-quiz-id");

  // Create an input field
  const inputField = document.createElement("input");
  inputField.setAttribute("type", "text");
  inputField.setAttribute("class", "editable-input");
  inputField.setAttribute("value", element.textContent.trim());

  // Store the current content of the element
  const oldContent = element.textContent.trim();

  // Replace the parent element with the input field
  element.replaceWith(inputField);

  // Focus on the input field
  inputField.focus();

  // Add event listener to handle input changes
  inputField.addEventListener("blur", function () {
    // Get the new data from the input field
    const newContent = inputField.value.trim();

    // Only update if the content has changed
    if (newContent !== oldContent) {
      // Update the database with the new data
      if (isH4) {
        updateQuizData(inputField, newContent, "", quizId);
      } else {
        updateQuizData(inputField, "", newContent, quizId);
      }

      // Replace the input field with the updated element
      const newElement = document.createElement(isH4 ? "h4" : "p");
      newElement.setAttribute("class", inputField.getAttribute("class"));
      newElement.textContent = newContent + ' ';
      newElement.setAttribute("data-original-content", newContent);
      newElement.setAttribute("data-quiz-id", quizId); // Set the quizId

      // Append the pencil icon span if necessary
      const pencilIcon = document.createElement("span");
      pencilIcon.setAttribute("class", "mdi mdi-pencil");
      newElement.appendChild(pencilIcon);

      inputField.replaceWith(newElement);

      // Reattach event listener to the new element
      attachEditListener(newElement);
    } else {
      // Restore the original content if no changes were made
      inputField.replaceWith(element);
      attachEditListener(element);
    }
  });
}


// Function to attach edit listener
function attachEditListener(element) {
  element.addEventListener("click", function (event) {
    if (!event.target.classList.contains("mdi-pencil")) {
      // Check if the element is <h4> or <p>
      const isH4 = element.tagName.toLowerCase() === "h4";
      handleEdit(element, isH4);
    }
  });
}

// Attach event listener to the initial <h4> and <p> elements
attachEditListener(document.getElementById("quizTitle"));
attachEditListener(document.getElementById("quizDescription"));




function publishExam(quiz_id) {
  // Show a confirmation dialog
  var userConfirmed = confirm("Are you sure you want to publish this exam?");

  // If the user confirmed, proceed with the AJAX request
  if (userConfirmed) {
    // Create an AJAX request
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "function/newquiz-function/publish_quiz.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Define a callback function to handle the response
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        // Show a notification or handle the response as needed
        alert(xhr.responseText);
        // Optionally, you can refresh the page or update the UI
        location.reload();
      }
    };

    // Send the request with the quiz_id
    xhr.send("quiz_id=" + quiz_id);
  }
}

