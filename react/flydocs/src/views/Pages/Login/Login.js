import React, { Component } from 'react';
import {Redirect} from 'react-router-dom';
import {PostData} from '../../../services/PostData';
import { Button, Card, CardBody, CardGroup, Col, Container, Form, Input, InputGroup, InputGroupAddon, InputGroupText, Row } from 'reactstrap';

import logo from './logo.png';
const Header = React.lazy(() => import('../Header/Header'));

class Login extends Component {

  constructor(){
    super();
    this.state = {
      username: '',
      password: '',
      redirectToReferrer: false
    };

    this.login = this.login.bind(this);
    this.onChange = this.onChange.bind(this);
  }

  login() {
    /*if(this.state.username && this.state.password){
      PostData('login',this.state).then((result) => {
        let responseJson = result;
        responseJson = {id:1,name:"Aniruddh"};
        if(responseJson){         
          sessionStorage.setItem('user',JSON.stringify(responseJson));
          this.setState({redirectToReferrer: true});
        }
      });
    }*/
    sessionStorage.setItem('user','1');
    this.setState({redirectToReferrer: true});
  }

  onChange(e){
    this.setState({[e.target.name]:e.target.value});
  }

  render() {

    if (this.state.redirectToReferrer) {
      return (<Redirect to={'/dashboard'}/>)
    }
    if(sessionStorage.getItem('user')){
      return (<Redirect to={'/dashboard'}/>)
    }

    return (
      <div className="app flex-row align-items-center">
        <Container>
          <Row className="justify-content-center">
            <Col md="5">
              <CardGroup>
                <Card className="p-4">
                  <CardBody>
                    <Form>
                      <Row>
                        <Col xs="1"></Col>
                        <Col xs="8" className="text-right">
                          <img src={logo} alt="logo" className="position-relative img-fluid" />
                        </Col>
                        <Col xs="2" className="text-right"></Col>
                      </Row>
                      <p className="text-muted">&nbsp;</p>
                      <InputGroup className="mb-3">
                        <InputGroupAddon addonType="prepend">
                          <InputGroupText>
                            <i className="icon-user"></i>
                          </InputGroupText>
                        </InputGroupAddon>
                        <Input type="email" name="username" placeholder="Username" onChange={this.onChange} placeholder="with a placeholder"  />
                      </InputGroup>
                      <InputGroup className="mb-4">
                        <InputGroupAddon addonType="prepend">
                          <InputGroupText>
                            <i className="icon-lock"></i>
                          </InputGroupText>
                        </InputGroupAddon>
                        <Input type="password" name="password"  placeholder="Password" onChange={this.onChange} placeholder="with a placeholder"  />
                      </InputGroup>
                      <Row>
                        <Col xs="6">
                          <Button color="success" className="px-4" onClick={this.login}>Login</Button>
                        </Col>
                        <Col xs="6" className="text-right">
                          <Button color="link" className="px-0">Forgot password?</Button>
                        </Col>
                      </Row>
                    </Form>
                  </CardBody>
                </Card>                
              </CardGroup>
            </Col>
          </Row>
        </Container>
      </div>
    );
  }
}

export default Login;
