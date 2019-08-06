import React, {Component} from 'react';
//import ReactDOM from 'react-dom';
import {Redirect} from 'react-router-dom';
import '../Login/Login.css';
import {postRequest} from '../../services/ServerRequest';


class Login extends Component {
    constructor(){
        super();
        this.state = {
            username:'',
            password:'',
            isAuthenticated: false
        };
        this.submitForLogin = this.submitForLogin.bind(this);
        this.funOnchange = this.funOnchange.bind(this);
        this.reset = this.reset.bind(this);
    }
    funOnchange(e){
        this.setState({[e.target.name]:e.target.value});
    }
    submitForLogin(event){
        
        //let respo = postRequest('checklogin',this.state);
        //alert(respo);
        event.preventDefault();
        postRequest('checklogin',this.state).then((result)=>{
            let responseJson = result;
           sessionStorage.setItem('userData',responseJson);
           this.setState({isAuthenticated:true});
        });

    }
    reset(){
        this.setState({username:'',password:'',isAuthenticated: false});
    }

    render (){
        if (this.state.redirectToReferrer) {
            return (<Redirect to={'/'}/>)
        }
         
        if(sessionStorage.getItem('userData')){
            return (<Redirect to={'/'}/>)
        }
        return (
                <div className="tableMainBackground">
                    <div className="whitebackground">
                        <span className="WelcomescreenHeadingpurple">Welcome To FLYdocs</span>
                        <div className="tableMiddleBackground_roundedcorner">
                            <div>
                                <form onSubmit={this.submitForLogin}>
                                    <span id="remainTime" ></span>
                                    <b>Username:</b> <input name="username" className="textInput" value={this.state.username} type="text" onChange={this.funOnchange} /><br />
                                    <br />
                                    <b>Password:</b> <input name="password" className="textInput" value={this.state.password} type="password" onChange={this.funOnchange}  />
                                    <br />
                                    <br />
                                    <input type="submit" value="SUBMIT" className="button" />
                                    <input type="button" onClick={this.reset} value="RESET" className="button" />
                                </form>
                            </div>
                        </div>
                </div>
            </div>
        );
    }
}
  
  export default Login;