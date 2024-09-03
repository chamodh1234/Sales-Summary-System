const loging = () =>{
    const userName = document.getElementById('userName')
    const password = document.getElementById('password')

    const req = new XMLHttpRequest()
    const formData = new FormData()

  if(userName.value !='' && password.value !=''){
    formData.append('userName',userName.value)
    formData.append('password',password.value)


    req.onreadystatechange = () =>{
      if(req.status ==200 && req.readyState ==4){
        if(req.responseText == 'user not found'){
          alert(req.responseText);
        }
      }
    }

     req.open("POST", "server/process/logingprocess.php", true);
    req.send(formData);

  }
   
}