
$(document).ready(function() {
    setTimeout(function() {
        $('.toast').removeClass("notification-error notification-success notification-usually");
    }, 5000); // 5000 миллисекунд = 5 секунд
});
function ShowDescTask(params) {
    document.querySelector('.modal1').style.display = "block";
    document.querySelector('.modal-title').innerText = params[1];
    const select = document.querySelector('#status').getElementsByTagName('option');
    for (let i = 0; i < select.length; i++) {
        if (select[i].value === params[2]) select[i].selected = true;
    }
    document.querySelector('.modal-body > p').innerText = params[0];
    document.querySelector('.modal-body > span').innerText = params[3];
}
function switchAgreement(){
    document.querySelector('#submit').disabled = !document.querySelector('#formSwitchCheckDefault').checked;
}
function closeNotification($classNotification){

    document.querySelector('.'+$classNotification).remove();
}
function hideWindow($class){
    document.querySelector('.'+$class).style.display="none";
}
function ShowWindWithAddTask() {
    document.querySelector('.modal').style.display = "block";
}