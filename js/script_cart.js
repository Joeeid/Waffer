let sidebar = document.querySelector('.sidebar');

document.querySelector('#cat-btn').onclick = () =>{
    sidebar.classList.toggle('active');
    navbar.classList.remove('active');
    searchForm.classList.remove('active');
    loginForm.classList.remove('active');
}

let searchForm = document.querySelector('.search-form');

document.querySelector('#search-btn').onclick = () =>{
    sidebar.classList.remove('active');
    searchForm.classList.toggle('active');
    loginForm.classList.remove('active');
    navbar.classList.remove('active');
}

let loginForm = document.querySelector('.login-form');

document.querySelector('#login-btn').onclick = () =>{
    sidebar.classList.remove('active');
    loginForm.classList.toggle('active');
    searchForm.classList.remove('active');
    navbar.classList.remove('active');
}

let navbar = document.querySelector('.navbar');

document.querySelector('#menu-btn').onclick = () =>{
    sidebar.classList.remove('active');
    navbar.classList.toggle('active');
    searchForm.classList.remove('active');
    loginForm.classList.remove('active');
}

window.onscroll = () =>{
    sidebar.classList.remove('active');
    searchForm.classList.remove('active');
    loginForm.classList.remove('active');
    navbar.classList.remove('active');
}

function showResult(str) {
    if (str.length==0) {
      document.getElementById("livesearch").innerHTML="";
      document.getElementById("livesearch").style.border="0px";
      return;
    }
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
      if (this.readyState==4 && this.status==200) {
        document.getElementById("livesearch").innerHTML=this.responseText;
        document.getElementById("livesearch").style.border="0px";
      }
    }
    xmlhttp.open("GET","livesearch.php?q="+str,true);
    xmlhttp.send();
  }

  function delay(time) {
    return new Promise(resolve => setTimeout(resolve, time));
  }

async function addToCart(id,count) {
  let quantity = document.getElementsByName("quantity")[count].value;
  if(!quantity){
    quantity = document.getElementsByName("quantity")[count].value = 1;
  }
  add = document.getElementsByName("add")[count];
  add.className = "btn-add";
  add.innerHTML="Adding...";
  await delay(1000);
  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=async function() {
    if (this.readyState==4 && this.status==200) {
      if(this.responseText != "Please Login First"){
      document.getElementsByName("trash")[count].style.visibility="visible";
      }
      add.innerHTML=this.responseText;
      cartQuantity();
      await delay(2000);
      add.className = "btn";
      add.innerHTML="Add to Cart";
    }
  }
  xmlhttp.open("GET","addtocart.php?id="+id+"&q="+quantity,true);
  xmlhttp.send();
}

async function removeFromCart(id,count) {
  add = document.getElementsByName("add")[count];
  add.className = "btn-add";
  add.innerHTML="Removing...";
  await delay(1000);
  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=async function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementsByName("trash")[count].style.visibility="hidden";
      add.innerHTML=this.responseText;
      cartQuantity();
      await delay(2000);
      add.className = "btn";
      add.innerHTML="Add to Cart";
    }
  }
  xmlhttp.open("GET","removefromcart.php?id="+id,true);
  xmlhttp.send();
}

function showCart() {

  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("shop").innerHTML=this.responseText;
    }
  }
  xmlhttp.open("GET","cart.php",true);
  xmlhttp.send();
}

async function removeCart(id,count) {
  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=async function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementsByName("item")[count].innerHTML = this.responseText;
      await delay(1000);
      cartQuantity();
      showCart();
    }
  }
  xmlhttp.open("GET","removefromcart.php?id="+id,true);
  xmlhttp.send();
}

function cartQuantity() {
  cart = document.getElementById("cart-btn");
  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=async function() {
    if (this.readyState==4 && this.status==200) {
      cart.dataset.count=this.responseText;
      cart.classList.add("added");
      await delay(1000);
      cart.classList.remove("added");
    }
  }
  xmlhttp.open("GET","cartquantity.php",true);
  xmlhttp.send();
}

window.onload = cartQuantity();