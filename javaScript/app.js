
function showPopup(type, amount,receiverFname,receiverLname) {
    console.log("app.js loaded");
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

function showTransactionDetails(){
    console.log("clicked");
    document.getElementById("transaction-details").style.display = "block";

}