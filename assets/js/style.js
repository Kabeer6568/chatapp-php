let activeUser = null;

function openChat(receiver_id){

    activeUser = receiver_id;


    document.getElementById('chatInput').style.display = block;

    loadMessages();

}