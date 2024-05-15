import {useState} from 'react'
import General from './General.jsx'
import Elements from './Elements.jsx'
import '../App.css'

function App() {
    const [menu, setMenu] = useState('general');

    return (
        <>
            {menu === 'general' ? <General setmenu={setMenu}/> : ''}
            {menu === 'elements' ? <Elements setmenu={setMenu}/> : ''}
        </>
    )
}

export default App
