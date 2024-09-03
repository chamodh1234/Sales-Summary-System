const loging = () => {
  const userName = document.getElementById("userName");
  const password = document.getElementById("password");

  const req = new XMLHttpRequest();
  const formData = new FormData();

  if (userName.value != "" && password.value != "") {
    formData.append("userName", userName.value);
    formData.append("password", password.value);

    req.onreadystatechange = () => {
      if (req.status == 200 && req.readyState == 4) {
        if (req.responseText == "user found") {
          window.location.href = "admin.php?comp=1001";
        } else if (req.responseText == "already loged") {
          alert("You can't login. Before you login here , you need to logout from the current machine")
          window.location.href = "loging.php";
        }
      }
    };

    req.open("POST", "../../server/process/logingprocess.php", true);
    req.send(formData);
  }
};

const logout = () => {
  const req = new XMLHttpRequest();
  const formData = new FormData();

  if (confirm("Do you need to logout ?")) {
    formData.append("lo", "");
    req.onreadystatechange = () =>{
      if(req.readyState ==4 && req.status ==200){
         window.location.reload()
      }
    }
    req.open("POST", "../../server/process/logout.php", true);
    req.send(formData);
   
  }
};

const productLoadingAddLorry = () => {
  const lorryId = document.getElementById("productLoadingSelectLorry");
  const refName = document.getElementById("productLoadingSelectRef");
  const route = document.getElementById("productLoadingSelectRoute");

  const req = new XMLHttpRequest();
  const formData = new FormData();
  console.log(route.value);
  if (lorryId.value == "" || refName.value == "" || route.value == "") {
    document.getElementById("productLoadingAddLorryAlert").innerHTML =
      "All Feilds are mandetory";
  } else {
    document.getElementById("productLoadingAddLorryAlert").innerHTML = "";
    formData.append("lorryId", lorryId.value);
    formData.append("refName", refName.value);
    formData.append("route", route.value);

    req.onreadystatechange = () => {
      if (req.readyState == 4 && req.status == 200) {
        if (req.responseText !== "Insert Successfull") {
          alert("Error : " + req.responseText);
        } else if (req.responseText == "Lorry Already Added") {
          alert("Lorry Already Added");
        } else {
          document.getElementById("productLoadingAddLorryBtn").remove();
          lorryId.value = "";
          refName.value = "";
          window.location.reload();
        }
      }
    };
    req.open("POST", "../../server/process/productLoadingAddLorry.php", true);
    req.send(formData);
  }
};

const productLoadingAddProduct = (lorryNumber) => {
  const productName = document.getElementById("productLoadingAddProductSelect");
  const req = new XMLHttpRequest();
  const formData = new FormData();
  if (productName.value != "" && lorryNumber != 0) {
    formData.append("productName", productName.value);
    formData.append("lorryNumber", lorryNumber);

    req.onreadystatechange = () => {
      if (req.readyState == 4 && req.status == 200) {
        if (req.responseText == "Product Added") {
          // document.getElementById('productLoadingAddProductBtn').remove()
          productName.value = "";
        } else if (req.responseText == "Product Already Added") {
          document.getElementById("productLoadingAddProductAlert").innerHTML =
            "Product Already Added";
        }
      }
    };

    req.open("POST", "../../server/process/productLoadingAddProduct.php", true);
    req.send(formData);
    document.getElementById("productLoadingAddProductAlert").innerHTML = "";
  } else {
    document.getElementById("productLoadingAddProductAlert").innerHTML =
      "All feilds are mandetory";
  }
};

const productLoadingMoveToLoaded = (productName, lorryNumber, condition) => {
  const loading = document.getElementById("loadingInput" + productName);
  const unloading = document.getElementById("unloadingInput" + productName);
  const unitPrice = document.getElementById("unitPriceInput" + productName);
  const sales = document.getElementById("salesInput" + productName);
  const value = document.getElementById("valueInput" + productName);
  const rtn = document.getElementById("rtnInput" + productName);
  const req = new XMLHttpRequest();
  const formData = new FormData();
  formData.append("submit", condition);
  formData.append("productName", productName);
  formData.append("lorryNumber", lorryNumber);

  if (condition == "uload" && unloading.value != "") {
    formData.append("unloading", unloading.value);
    formData.append("unitPrice", unitPrice.value);
    formData.append("sales", sales.value);
    formData.append("value", value.value);
    formData.append("rtn", rtn.value);
  } else if (condition != "load") {
    document.getElementById(
      "productLoadingInputAlert" + productName
    ).innerHTML = "Enter valid inputs";
  }

  req.onreadystatechange = () => {
    if (req.readyState == 4 && req.status == 200) {
      if (req.responseText == "Input loading value") {
        document.getElementById(
          "productLoadingInputAlert" + productName
        ).innerHTML = "Input Valid Loading Value";
      } else if (req.responseText == "Input Numeric Value") {
        document.getElementById(
          "productLoadingInputAlert" + productName
        ).innerHTML = "Input Numeric Value";
      } else if (req.responseText == "Moved to Loaded") {
window.location.reload()
      //  document.getElementById("productLoadedBtn").remove();
      } else if (req.responseText == "Moved to Unloaded") {
        window.location.reload()
        //document.getElementById(
        //  "productLoadingInputAlert" + productName
        //).innerHTML = "";
       // document.getElementById("productUnloadBtn").remove();
      } else {
        alert(req.responseText);
      }
    }
  };
  if (!isNaN(loading.value) && loading.value != "") {
    formData.append("loading", loading.value);
  }
  console.log(loading.value);

  req.open("POST", "../../server/process/productLoadingMoveToLoaded.php", true);
  req.send(formData);
};

const salesAndValueCalculation = (productName) => {
  const loading = document.getElementById("loadingInput" + productName);
  const unloading = document.getElementById("unloadingInput" + productName);
  const unitPrice = document.getElementById("unitPriceInput" + productName);
  const sales = document.getElementById("salesInput" + productName);
  const value = document.getElementById("valueInput" + productName);

  if (loading.value - unloading.value < 0 || unloading.value == "") {
    sales.value = "error";
    value.value = "error";
  } else {
    sales.value = loading.value - unloading.value;
    value.value = sales.value * unitPrice.value;
  }
};

const registerProduct = () => {
  const productName = document.getElementById("productNameRegInput");
  const unitPrice = document.getElementById("productUnitPriceRegInput");
  const req = new XMLHttpRequest();
  const formData = new FormData();

  if (productName.value != "" && unitPrice.value != "") {
    formData.append("productName", productName.value.trim());
    formData.append("unitPrice", unitPrice.value);
    document.getElementById("productRegAlert").innerHTML = "";
    req.onreadystatechange = () => {
      if (req.readyState == 4 && req.status == 200) {
        if (req.responseText != "done") {
          document.getElementById("productRegAlert").innerHTML =
            "Product Already Added";
        } else if (req.responseText == "done") {
          document.getElementById("productRegAlert").innerHTML = "";
          productName.value = "";
          unitPrice.value = "";
        } else {
          alert(req.responseText);
        }
      }
    };
    req.open("POST", "../../server/process/productRegister.php", true);
    req.send(formData);
  } else {
    document.getElementById("productRegAlert").innerHTML = "Input valid data";
  }
};

const addProductToInventory = () => {
  const productName = document.getElementById("inventoryProductNameInput");
  const productCount = document.getElementById("inventoryProductCountInput");

  const req = new XMLHttpRequest();
  const formData = new FormData();

  if (productCount.value != "" && productName.value != "") {
    formData.append("productName", productName.value);
    formData.append("productCount", productCount.value);

    req.onreadystatechange = () => {
      if (req.readyState == 4 && req.status == 200) {
        if (req.responseText == "insert") {
          productCount.value = "";
          productName.value = "";
        } else if (req.responseText == "update") {
          productCount.value = "";
          productName.value = "";
        } else {
          alert(req.responseText);
        }
      }
    };

    document.getElementById("inventoryAddProductAlert").innerHTML = "";
    req.open("POST", "../../server/process/addProductToInventory.php", true);
    req.send(formData);
  } else {
    document.getElementById("inventoryAddProductAlert").innerHTML =
      "Input valid Data";
  }
};

const addProductPurchaseToInventory = () => {
  const productName = document.getElementById(
    "inventoryPruchaseProductNameInput"
  );
  const productCount = document.getElementById(
    "inventoryProductPurchaseCountInput"
  );

  const req = new XMLHttpRequest();
  const formData = new FormData();

  if (productCount.value != "" && productName.value != "") {
    formData.append("productName", productName.value);
    formData.append("productCount", productCount.value);

    req.onreadystatechange = () => {
      if (req.readyState == 4 && req.status == 200) {
        if (req.responseText == "insert") {
          productCount.value = "";
          productName.value = "";
        } else if (req.responseText == "update") {
          productCount.value = "";
          productName.value = "";
        } else {
          alert(req.responseText);
        }
      }
    };

    document.getElementById("inventoryAddPurchaseAlert").innerHTML = "";
    req.open(
      "POST",
      "../../server/process/addProductPurchaseToInventory.php",
      true
    );
    req.send(formData);
  } else {
    document.getElementById("inventoryAddPurchaseAlert").innerHTML =
      "Input valid Data";
  }
};

const finalCashStatement = (lorryNumber) => {
  const fiveThousand = document.getElementById("fiveth");
  const thousand = document.getElementById("th");
  const fiveHundred = document.getElementById("fiveh");
  const hundred = document.getElementById("hun");
  const fifty = document.getElementById("fif");
  const twenty = document.getElementById("twn");
  const coins = document.getElementById("coins");
  const total = document.getElementById("total");
  const totalCash = document.getElementById("totalch");
  const totalCheques = document.getElementById("totalche");
  const totalCreditBill = document.getElementById("totalcrebill");
  const Discount = document.getElementById("discount");
  const expenses = document.getElementById("expe");
  const grandTotal = document.getElementById("grand");
  const ReceiptForPrevBill = document.getElementById("reciptfprevbil");
  const netSale = document.getElementById("netsale");
  const StockValAfterRtn = document.getElementById("stockvlafrtn");
  const difference = document.getElementById("diff");
  const extraEarned = document.getElementById("exearn");
  const differenceIfAny = document.getElementById("diffifany");

  const req = new XMLHttpRequest();
  const formData = new FormData();

  if (
    fiveThousand.value != "" &&
    thousand.value != "" &&
    fiveHundred.value != "" &&
    hundred.value != "" &&
    fifty.value != "" &&
    twenty.value != "" &&
    coins.value != "" &&
    total.value != "" &&
    totalCash.value != "" &&
    totalCheques.value != "" &&
    totalCreditBill.value != "" &&
    Discount.value != "" &&
    grandTotal.value != "" &&
    ReceiptForPrevBill.value != "" &&
    netSale.value != "" &&
    StockValAfterRtn.value != "" &&
    difference.value != "" &&
    extraEarned.value != "" &&
    differenceIfAny.value != ""
  ) {
    formData.append("fiveth", fiveThousand.value * 5000);
    formData.append("th", thousand.value * 1000);
    formData.append("fiveh", fiveHundred.value * 500);
    formData.append("hun", hundred.value * 100);
    formData.append("fif", fifty.value * 50);
    formData.append("twn", twenty.value * 20);
    formData.append("coins", coins.value);
    formData.append("total", total.value);
    formData.append("totalch", totalCash.value);
    formData.append("totalche", totalCheques.value);
    formData.append("totalcrebill", totalCreditBill.value);
    formData.append("discount", Discount.value);
    formData.append("expe", expenses.value);
    formData.append("grand", grandTotal.value);
    formData.append("reciptfprevbil", ReceiptForPrevBill.value);
    formData.append("netsale", netSale.value);
    formData.append("stockvlafrtn", StockValAfterRtn.value);
    formData.append("diff", difference.value);
    formData.append("exearn", extraEarned.value);
    formData.append("diffifany", differenceIfAny.value);
    formData.append("lorryNumber", lorryNumber);


    req.onreadystatechange = () =>{
      if(req.readyState == 4 && req.status ==200){
        if(req.responseText == 'added'){
          window.location.reload()
        }else{
          alert(req.responseText)
        }
      }
    }

    req.open("POST", "../../server/process/finalCashStatement.php", true);
    req.send(formData);
  } else {
    (fiveThousand.value = 0),
      (thousand.value = 0),
      (fiveHundred.value = 0),
      (hundred.value = 0),
      (fifty.value = 0),
      (twenty.value = 0),
      (coins.value = 0),
      (total.value = 0),
      (totalCash.value = 0),
      (totalCheques.value = 0),
      (totalCreditBill.value = 0),
      (Discount.value = 0),
      (grandTotal.value = 0),
      (ReceiptForPrevBill.value = 0),
      (netSale.value = 0),
      (StockValAfterRtn.value = 0),
      (difference.value = 0),
      (extraEarned.value = 0),
      (differenceIfAny.value = 0);
  }
};

const checkSummary = () => {
  const Date = document.getElementById("summaryDateInput");

  const req = new XMLHttpRequest();
  const formData = new FormData();
  formData.append("a", "");

  req.onreadystatechange = () => {
    if (req.readyState == 4 && req.status == 200) {
      window.location.href =
        "?comp=1006&dt=" +
        Date.value;
    }
  };

  req.open("POST", "../components/summary.php", true);
  req.send(formData);
};

const goBack = () => {
  window.history.back();
};

const monthlyView = () => {
  const month = document.getElementById("monthSlectSummaryInput");
  const year = document.getElementById("yearSelectSummary");

  const req = new XMLHttpRequest();
  const formData = new FormData();

  if (month.value != "" && year.value != "") {
    formData.append("month", month.value);
    formData.append("year", year.value);

    window.location.href =
      "?comp=1006&m=" +
      month.value +
      "&y=" +
      year.value;
  }
};

const selectRefMonthlySummary = (month, year, lorryNumber) => {
  const refId = document.getElementById("refInputMonthlySummary");

  console.log(month, year, lorryNumber, refId.value);
  if (refId.value != "" && month != "" && year != "" && lorryNumber != "") {
    window.location.href = `?comp=1006&m=${month}&y=${year}&mss=true&lr=${lorryNumber}&rid=${refId.value}`;
  }
};

const registerLorry = () => {
  const lorryNumebr = document.getElementById("lorryNumberRegisterInput");
  const req = new XMLHttpRequest();
  const formData = new FormData();

  if (lorryNumebr.value != "") {
    formData.append("lorryNumber", lorryNumebr.value);

    req.onreadystatechange = () => {
      if (req.readyState == 4 && req.status == 200) {
        if (req.responseText == "Insert") {
          // document.getElementById('lorryRegisterBtn').remove()
          window.location.reload();
        } else {
          alert(req.responseText);
        }
      }
    };
    req.open("POST", "../../server/process/lorryRegister.php", true);
    req.send(formData);
  }
};

const registerRoute = () => {
  const route = document.getElementById("routeRegisterInput");

  const req = new XMLHttpRequest();
  const formData = new FormData();

  if (route.value != "") {
    formData.append("route", route.value);

    req.onreadystatechange = () => {
      if (req.readyState == 4 && req.status == 200) {
        if (req.responseText == "Insert") {
          // document.getElementById('lorryRegisterBtn').remove()
          window.location.reload();
        } else {
          alert(req.responseText);
        }
      }
    };
    req.open("POST", "../../server/process/routeRegister.php", true);
    req.send(formData);
  }
};
const registerRef = () => {
  const route = document.getElementById("refRegisterInput");

  const req = new XMLHttpRequest();
  const formData = new FormData();

  if (route.value != "") {
    formData.append("ref", route.value);

    req.onreadystatechange = () => {
      if (req.readyState == 4 && req.status == 200) {
        if (req.responseText == "Insert") {
          // document.getElementById('lorryRegisterBtn').remove()
          window.location.reload();
        } else {
          alert(req.responseText);
        }
      }
    };
    req.open("POST", "../../server/process/refRegister.php", true);
    req.send(formData);
  }
};

const printDailyLorrySummary = (date, lorryNumber) => {
  //const req = new XMLHttpRequest();
  //  const formData = new FormData();
  console.log(date);
  window.location.href = `../../pdf.php?dt=${date}&lr=${lorryNumber}`;
  //formData.append('date',date)
  //formData.append('lorryNumber',lorryNumber)

  //req.open("POST", "../../pdf.php", true);
  //req.send(formData);
};

const printMonthlySummary = (month, year, lorryNumber, refId) => {
  window.location.href = `../../monthlypdf.php?m=${month}&y=${year}&lr=${lorryNumber}&rid=${refId}`;
};



const calcutaleTotalAndCash = () =>{
  const fiveThousand = document.getElementById("fiveth");
  const thousand = document.getElementById("th");
  const fiveHundred = document.getElementById("fiveh");
  const hundred = document.getElementById("hun");
  const fifty = document.getElementById("fif");
  const twenty = document.getElementById("twn");
  const coins = document.getElementById("coins");

  const total = document.getElementById("total");
  const totalCash = document.getElementById("totalch");
 
  var totalval = 0;

  total.value = (fiveThousand.value*5000 +thousand.value*1000 +fiveHundred.value*500 + hundred.value*100 +fifty.value*50 + twenty.value*20 )
  totalval = Number(total.value) + Number(coins.value)
  
  total.value = totalval;
  //console.log(totalval)

  totalCash.value = totalval


}


const calculateGrandTotal = () =>{

  const totalCash = document.getElementById("totalch");
  const totalCheques = document.getElementById("totalche");
  const totalCreditBill = document.getElementById("totalcrebill");
  const Discount = document.getElementById("discount");
  const expenses = document.getElementById("expe");
  const grandTotal = document.getElementById("grand");

var grandtotal = Number(totalCash.value) + Number(totalCheques.value) + Number(totalCreditBill.value) + Number(Discount.value) + Number(expenses.value);

  grandTotal.value = grandtotal.toString()

  //console.log(grandtotal)

}

const calcutaleNetSale = () =>{
  const grandTotal = document.getElementById("grand");
  const ReceiptForPrevBill = document.getElementById("reciptfprevbil");
  const netSale = document.getElementById("netsale");
  const difference = document.getElementById("diff");
  const StockValAfterRtn = document.getElementById("stockvlafrtn");


  netSale.value = Number(grandTotal.value) - Number(ReceiptForPrevBill.value)

  difference.value = (Number(netSale.value) - Number(StockValAfterRtn.value)).toString()

}

const calculateDifferenceIfAny = ()=>{
  const difference = document.getElementById("diff");
  const extraEarned = document.getElementById("exearn");
  const differenceIfAny = document.getElementById("diffifany");

  differenceIfAny.value = (Number(difference.value)- Number(extraEarned.value)).toString()
}


const productConnect = () =>{
  const connect = document.getElementById('inventoryConnectProductNameInput')
  const to = document.getElementById('inventoryConnectToProductNameInput')

  const req = new XMLHttpRequest();
  const formData = new FormData();

  if(connect.value != '' && to.value != ''){
    formData.append('connect',connect.value)
    formData.append('to',to.value)
    document.getElementById('inventoryAddConnectAlert').innerHTML = ''

    req.open('POST','../../server/process/connectProduct.php',true)
    req.send(formData)
  }else{
    document.getElementById('inventoryAddConnectAlert').innerHTML = 'All fields are mandetory'
  }
}


const deleteProduct = () =>{
  const productName = document.getElementById('productNameRegInputDel')

  const req = new XMLHttpRequest();
  const formData = new FormData();


  if(productName.value !=''){

    formData.append('productName',productName.value);

    req.onreadystatechange = () =>{
      if(req.readyState == 4 && req.status==200){
        if(req.responseText =='product deleted'){
          window.location.reload()
        }else{
          alert(req.responseText)
        }
      }
    }

    req.open('POST','../../server/process/deleteProduct.php',true)
    req.send(formData)

  }

  
}