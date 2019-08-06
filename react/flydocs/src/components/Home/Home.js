import React from 'react';

import {Redirect} from 'react-router-dom';

const Home = () => {
    if(!sessionStorage.getItem('userData')){
        return (<Redirect to={'/Login'}/>)
    }
    return (
        <div>
            In Home
        </div>
    );
}

export default Home;