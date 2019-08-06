import React,{Component} from 'react';
import '../Login/Login.css';

class LoginHeader extends Component{
    render(){
        return (
            <div className="topheaderclass">
                <div className="logoclass" ></div>
                <div className="powerby" >powered by FLYdocs</div>
            </div>
        );
    }
}

export default LoginHeader;