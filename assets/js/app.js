document.addEventListener("click",function(e){
 const x=e.target.closest("[data-confirm]");
 if(x && !confirm(x.getAttribute("data-confirm"))) e.preventDefault();
});