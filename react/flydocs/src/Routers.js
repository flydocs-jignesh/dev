import React from 'react';
import {BrowserRouter,Route,Switch} from 'react-router-dom';

import Login from './components/Login/Login';
import Home from './components/Home/Home';

const Routes = () => (
    <BrowserRouter >
        <Switch>
            <Route exact path="/" component={Home}/>
            <Route path="/login" component={Login}/>
        </Switch>
    </BrowserRouter>
  );
  
  export default Routes;