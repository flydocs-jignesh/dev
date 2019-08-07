import React, { Component } from 'react';

import './Header.css';
import logo from './logo.png';
import {
  Container, Row, Col, Form, Input, Button, Navbar, Nav,
  NavbarBrand, NavLink, NavItem, UncontrolledDropdown,
  DropdownToggle, DropdownMenu, DropdownItem
} from 'reactstrap';

const Header = () => (
    <Navbar color="light" light expand="xs" className="calloutHeader">
      <Container>
        <Row noGutters className="position-relative w-100 align-items-left">
          <Col className="d-flex justify-content-xs-start justify-content-lg-left">
            <NavbarBrand className="d-inline-block p-0" href="/">
              <img src={logo} alt="logo" className="position-relative img-fluid" />
            </NavbarBrand>
          </Col>
        </Row>
      </Container>
    </Navbar>
);

export default Header;