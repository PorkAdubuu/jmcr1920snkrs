

function showNotification(message, duration) {
    const notification = document.getElementById('notification');
    notification.innerHTML = message;
    notification.classList.remove('hide');
    notification.classList.add('show');
    setTimeout(() => {
        notification.classList.remove('show');
        notification.classList.add('hide');
    }, duration);
}