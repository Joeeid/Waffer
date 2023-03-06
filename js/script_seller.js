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


document.querySelector('#close-edit').onclick = () =>{
   document.querySelector('.edit-form-container').style.display = 'none';
   window.location.href = 'seller.php';
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

function validateForm(){
   category = document.getElementById("categories");
   if(category.value == 0){
      category.style.color="red";
      category.style.fontWeight ="bold";
      return false;
   }
   return true;
   
}

function validateForm2(){
   category = document.getElementById("categories");
   product = document.getElementById("products");
   if(category.value == 0){
      category.style.color="red";
      category.style.fontWeight ="bold";
      return false;
   }
   else if(products.value == 0){
         product.style.color="red";
         product.style.fontWeight ="bold";
         return false;
      }
   return true;
}