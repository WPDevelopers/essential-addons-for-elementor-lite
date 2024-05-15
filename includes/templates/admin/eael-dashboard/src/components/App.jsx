import React, {useState} from 'react'
import Header from "./Header.jsx";
import Menu from "./Menu.jsx";
import General from './General.jsx'
import Elements from './Elements.jsx'
import Extensions from './Extensions.jsx'
import Tools from './Tools.jsx'
import '../App.css'

function App() {
    const [menu, setMenu] = useState('general');

    return (
        <>
            <section id="ea__dashboard--wrapper" className="ea__dashboard--wrapper">
                <Header/>
                <section className={menu === 'elements' ? 'ea__section-wrapper ea__main-wrapper flex' : 'ea__section-wrapper ea__main-wrapper flex gap-4'}>
                    <Menu setmenu={setMenu} menu={menu}/>
                    {menu === 'general' ? <General setmenu={setMenu}/> : ''}
                    {menu === 'elements' ? <Elements setmenu={setMenu}/> : ''}
                    {menu === 'extensions' ? <Extensions setmenu={setMenu}/> : ''}
                    {menu === 'tools' ? <Tools setmenu={setMenu}/> : ''}
                </section>
            </section>
        </>
    )
}

export default App
