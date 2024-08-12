import {useEffect} from "react";
import consumer from "../context";

function Toasts() {
    const {eaState, eaDispatch} = consumer(),
        imageSrc = eaState.toastType === 'success' ? 'images/success.svg' : (eaState.toastType === 'warning' ? 'images/warning.svg' : 'images/error.svg'),
        contentClasses = eaState.toastType === 'success' ? 'toaster-content' : (eaState.toastType === 'warning' ? 'toaster-content ea__warning' : 'toaster-content ea__error');

    useEffect(() => {
        if (eaState.toasts === true) {
            setTimeout(() => {
                eaDispatch({type: 'CLOSE_TOAST'});
            }, 2000);
        }
    }, [eaState.toasts]);
    return (
        <>
            <div className='ea__toaster-wrapper'>
                <div className={contentClasses}>
                    <div className='flex items-center justify-between gap-2 flex-1'>
                        <div className='flex gap-2 items-center'>
                            <img src={localize.eael_dashboard.reactPath + imageSrc} alt="logo icon"/>
                            <h5>{eaState.toastMessage}</h5>
                        </div>
                        <i className='ea-dash-icon ea-close' onClick={() => {
                            eaDispatch({type: 'CLOSE_TOAST'});
                        }}></i>
                    </div>
                </div>
            </div>
        </>
    );
}

export default Toasts;