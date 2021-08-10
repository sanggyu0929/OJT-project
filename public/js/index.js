let signUpClick = document.getElementById('sign-btn');
let loginClick = document.getElementById('login-btn');
let token;
let metaName = 'csrf-token';
signUpClick.onclick = function(e){
    // const xhr = new XMLHttpRequest();

    // xhr.open('POST', '/login/post');

    // // 클라이언트가 서버로 전송할 데이터의 MIME-type 지정: json
    // xhr.setRequestHeader('Content-type', 'application/json');

    // const data = { id: 3, title: 'JavaScript', author: 'Park', price: 5000};

    // xhr.send(JSON.stringify(data));


    // // XMLHttpRequest.readyState 프로퍼티가 변경(이벤트 발생)될 때마다 onreadystatechange 이벤트 핸들러가 호출된다.
    // xhr.onreadystatechange = function (e) {
    // // readyStates는 XMLHttpRequest의 상태(state)를 반환
    // // readyState: 4 => DONE(서버 응답 완료)
    //     if (xhr.readyState !== XMLHttpRequest.DONE) return;

    //     // status는 response 상태 코드를 반환 : 200 => 정상 응답
    //     if(xhr.status === 200) {
    //         console.log(xhr.responseText);
    //     } else {
    //         console.log('Error!');
    //     }
    // };
    e.preventDefault();
    let email = document.getElementById('sign-email').value;
    let name = document.getElementById('sign-name').value;
    let pw = document.getElementById('sign-pw').value;
    let pwChk = document.getElementById('sign-pw-chk').value;
    let nomatch = document.getElementById('nomatch');

    function getToken(){
            const metas = document.getElementsByTagName('meta');
          
            for (let i = 0; i < metas.length; i++) {
              if (metas[i].getAttribute('name') === metaName) {
                // return metas[i].getAttribute('content');
                token = metas[i].getAttribute('content');
            }
        }
    } 
    

    getToken();
      
      
   

    let postUrl = '/sign-up/post';

  
        // let opts = {
        //     method: 'POST',
        //     body: `{
        //         email: ${email},
        //         name: ${name},
        //         pw: ${pw}
        //     }`,
        //     headers: {
        //         "X-CSRF-TOKEN" : _token,
        //         "Content-Type": "application/json",
        //         "Accept" : "application/json"
        //     }
        // };
        // fetch('/sign-up/post',opts).then(function(response) {
        //     console.log(response.json());
        // });
        fetch("/sign-up/post", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                "Accept" : "application/json"
            },
            body: JSON.stringify({'email': email,'name': name,'pw' : pw,'pwChk' : pwChk})
        }).then(
            res => res.json()
        ).then(function(response) {
            let res = JSON.stringify(response)
            console.log(res);
            if(res === '["success"]') {
                location.href='/login';
            }
        }).catch(err => console.log(err));
    
    console.log(email, name, pw, pwChk);
}

// loginClick.onclick = function(e) {
//     e.preventDefault();
//     let loginEmail = document.getElementById('login-email').value;
//     let loginPw = document.getElementById('login-pw').value;

//     function getToken(){
//         const metas = document.getElementsByTagName('meta');
      
//         for (let i = 0; i < metas.length; i++) {
//           if (metas[i].getAttribute('name') === metaName) {
//             // return metas[i].getAttribute('content');
//             token = metas[i].getAttribute('content');
//           }
//         }
//     } 

//     getToken();

//     fetch("/login/post", {
//         method: 'POST',
//         headers: {
//             'X-CSRF-TOKEN': token,
//             'Content-Type': 'application/json',
//             "Accept" : "application/json"
//         },
//         body: JSON.stringify({'email':loginEmail,'pw' : loginPw})
//     }).then(
//         res => res.json()
//     ).then(function(response) {
//             let res = JSON.stringify(response)
//             console.log(res);
//     }).catch(err => console.log(err));
// }

