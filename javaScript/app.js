
function showPopup(result, amount,destination,receiverFname,receiverLname,wallet) {
    const popup = document.getElementById("transaction-popup");
    const title = document.getElementById("popup-title");
    const message = document.getElementById("popup-message");
    popup.classList.remove("hidden");
    console.log("test");
    console.log(result)
    if(result === "Not enough funds"){
        console.log("founds");
        message.innerHTML =`Your transfer of £${amount} has failed, you dont have enough balance`;
    }
    else if(result === "Invalid amount"){
        console.log("founds");
        message.innerHTML =`The transfer has failed, the amount entered is invalid`;
    }
    else if(result === "Recipient not found"){
        console.log("founds");
        message.innerHTML = `The transfer has failed, the recipient does not exists`;
    }
    else if (result === "success" && destination === "person"){
        console.log("founds");
        title.innerHTML = "Transaction successfull";
        message.textContent = `Your transfer of £${amount} to ${receiverFname} ${receiverLname} was successful`;
    }
    else if (result === "success" && destination === "wallet"){
        console.log("founds");
        title.innerHTML = "Transaction successfull";
        message.textContent = `Your transfer of £${amount} to ${wallet} wallet was successful`;
    }
}

function closePopup() {
    document.getElementById("transaction-popup").classList.add("hidden");
}

function showTransactionDetails(data){
    console.log("clicked");
    console.log(data);
    console.log(data.status);
    if (data.status === "successful"){
        console.log("successful")
        document.getElementById("transaction-status").innerText = "Transaction completed";
        document.getElementById("transaction-type").innerText = data.type;
        document.getElementById("transaction-amount").innerText = data.amount;
        document.getElementById("transaction-from").innerText = data.sender_firstname;
        document.getElementById("transaction-to").innerText = data.receiver_firstname;
        document.getElementById("transaction-date").innerText = data.transaction_date;
    }
    if(data.status === "failed"){
        console.log("fail")
        document.getElementById("transaction-status").innerText = "Transaction failed";
        document.getElementById("transaction-type").innerText = data.type;
        document.getElementById("transaction-amount").innerText = data.amount;
        document.getElementById("transaction-from").innerText = data.sender_firstname;
        document.getElementById("transaction-to").innerText = data.receiver_firstname;
        document.getElementById("fail-reason").innerText = data.fail_reason;
        document.getElementById("transaction-date").innerText = data.transaction_date;
        
    }
    console.log("nothing")
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