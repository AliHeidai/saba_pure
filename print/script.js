 function printClose(){
   printPage();
   window.onafterprint = function () {
      window.close();
  }
}
 
async function printPage(){
   window.print();
}

async function closePage(){
  window.close();
}

printClose();
    
    
 