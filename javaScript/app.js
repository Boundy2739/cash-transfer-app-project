
function showPopup(type, amount,receiverFname,receiverLname) {
    const popup = document.getElementById("transaction-popup");
    const title = document.getElementById("popup-title");
    const message = document.getElementById("popup-message");
    popup.classList.remove("hidden");

    if(type === 'success'){
        title.innerHTML = "Transaction successfull";
        message.textContent = `Your transfer of £${amount} to ${receiverFname} ${receiverLname} was successful`;
    }

    else{
        message.innerHTML = 'Your transfer as failed';
    }
}

function closePopup() {
    document.getElementById("transaction-popup").classList.add("hidden");
}

function showTransactionDetails(data){
    console.log("clicked");
    document.getElementById("transaction-status").innerText = "Successful";
    document.getElementById("transaction-amount").innerText = data.amount;
    document.getElementById("transaction-from").innerText = data.sender_firstname;
    document.getElementById("transaction-to").innerText = data.receiver_firstname;
    openModal();
    

}


function openModal(){
    
    const modal_container = document.getElementById("modal_container");
    modal_container.classList.add('show');
}
function closeModal(){
    const modal_container = document.getElementById("modal_container");
    modal_container.classList.remove('show');
}
    
