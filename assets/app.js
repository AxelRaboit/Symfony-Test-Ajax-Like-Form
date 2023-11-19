/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

document.addEventListener("DOMContentLoaded", () => {
    const likeButtons = document.querySelectorAll('.like-button');

    likeButtons.forEach(button => {
        button.addEventListener('click', function() {

            if (!document.querySelector('[data-user-id]').dataset.userId) {
                window.location.href = '/login';
                return;
            }

            const articleId = this.dataset.article;

            fetch(`/like/${articleId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('like-count-' + articleId).innerText = data.likes;
                    const likeIcon = document.getElementById('like-icon-' + articleId);

                    if (data.status === 'like_added') {
                        likeIcon.classList.remove('far');
                        likeIcon.classList.add('fas');
                    } else {
                        likeIcon.classList.remove('fas');
                        likeIcon.classList.add('far');
                    }
                });
        });
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const contactForm = document.getElementById('contact-form');
    const contactSubmissions = document.getElementById('contact-submissions');
    const successMessage = document.createElement('div');

    contactForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(contactForm);

        fetch('/contact', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const submissionDiv = document.createElement('div');
                    submissionDiv.classList.add('submission');
                    submissionDiv.innerHTML = `<p><strong>${data.formData.subject}</strong></p><p>${data.formData.body}</p>`;

                    contactSubmissions.prepend(submissionDiv);

                    successMessage.textContent = 'Votre message a été envoyé avec succès!';
                    successMessage.classList.add('success-message');
                    contactForm.appendChild(successMessage);

                    setTimeout(() => successMessage.remove(), 3000);

                    contactForm.reset();
                } else {
                    console.error('Submission error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
});


