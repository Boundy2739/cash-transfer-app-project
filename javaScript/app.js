
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
    
/*Display input fields for editing user peronal details*/
function enableEdit(field, submitBtn, editBtn, cancelBtn) {
    const elements = document.getElementsByClassName(field);

    for (let i = 0; i < elements.length; i++) {
        elements[i].classList.add('active');
    }
    document.getElementById(submitBtn).style.display = "inline-block";
    document.getElementById(cancelBtn).style.display = "inline-block";
    document.getElementById(editBtn).style.display = "none";
}


/*Hides input fields */
function disableEdit(field, submitBtn, editBtn, cancelBtn) {
    const elements = document.getElementsByClassName(field);

    for (let i = 0; i < elements.length; i++) {
        elements[i].classList.remove('active');
        console.log(1);
        
    }
    document.getElementById(submitBtn).style.display = "none";
    document.getElementById(cancelBtn).style.display = "none";
    document.getElementById(editBtn).style.display = "inline-block";
}

function confirmChanges() {
    return confirm("Are you sure you want to apply these changes?");
}