import {useState} from 'react'
import Header from "./Header.jsx";
import Menu from "./Menu.jsx";
import General from './General.jsx'
import Elements from './Elements.jsx'
import Extensions from './Extensions.jsx'
import Tools from './Tools.jsx'
import Integration from './Integration.jsx'
import Premium from './Premium.jsx'
import '../App.css'

function App() {
    const [menu, setMenu] = useState('General');

    return (
        <>
            <section id="ea__dashboard--wrapper" className="ea__dashboard--wrapper">
                <Header/>
                <section
                    className={menu === 'elements' ? 'ea__section-wrapper ea__main-wrapper flex' : 'ea__section-wrapper ea__main-wrapper flex gap-4'}>
                    <Menu setmenu={setMenu} menu={menu}/>
                    {menu === 'General' ? <General setmenu={setMenu}/> : ''}
                    {menu === 'Elements' ? <Elements setmenu={setMenu}/> : ''}
                    {menu === 'Extensions' ? <Extensions setmenu={setMenu}/> : ''}
                    {menu === 'Tools' ? <Tools setmenu={setMenu}/> : ''}
                    {menu === 'Integration' ? <Integration setmenu={setMenu}/> : ''}
                    {menu === 'Go Premium' ? <Premium setmenu={setMenu}/> : ''}
                </section>
            </section>
        </>
    )
}

export default App
