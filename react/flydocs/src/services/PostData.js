export function PostData(type, userData) {
    //let BaseURL = 'https://api.thewallscript.com/restful/';
    let TokenURL = 'http://192.168.100.15:88/Dev/dev/slim/public/api';

    return new Promise((resolve, reject) =>{
        fetch(TokenURL+'/login/varifyUser', {
            method: 'POST',
            body: JSON.stringify(userData)
          })
          .then((response) => response.json())
          .then((res) => {
            resolve(res);
          })
          .catch((error) => {
            reject(error);
          });
      });
}