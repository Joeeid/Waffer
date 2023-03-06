let menu = document.querySelector('#menu-btn');
let navbar = document.querySelector('.header .navbar');

menu.onclick = () =>{
   menu.classList.toggle('fa-times');
   navbar.classList.toggle('active');
};

window.onscroll = () =>{
   menu.classList.remove('fa-times');
   navbar.classList.remove('active');
};

function cat(e) {
   products = document.getElementById("products");
   var xmlhttp=new XMLHttpRequest();
   xmlhttp.onreadystatechange=async function() {
     if (this.readyState==4 && this.status==200) {
       products.innerHTML=this.responseText;
     }
   }
   xmlhttp.open("GET","sellercat.php?c="+e.value,true);
   xmlhttp.send();
 }

 function delivery() {
  dest1 = document.getElementById("dest1").value;
  dest2 = document.getElementById("dest2").value;
  charge = document.getElementById("charge");
  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=async function() {
    if (this.readyState==4 && this.status==200) {
      charge.value=this.responseText;
    }
  }
  xmlhttp.open("GET","deliverycharge.php?d1="+dest1+"&d2="+dest2,true);
  xmlhttp.send();
}

function validateForm(){
  category = document.getElementById("categories");
  if(category.value == 0){
     category.style.color="red";
     category.style.fontWeight ="bold";
     return false;
  }
  return true;
}