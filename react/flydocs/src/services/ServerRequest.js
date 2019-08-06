const BaseURL = 'http://192.168.100.5:88/';

const getRequest = (name) => {
    alert(name);
}

const postRequest = (actionName, userData) => {
    return new Promise((resolve, reject) =>{
    
        fetch(BaseURL+actionName, {
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

const deleteRequest = (name) => {
    alert(name);
}

export {getRequest,postRequest,deleteRequest};

