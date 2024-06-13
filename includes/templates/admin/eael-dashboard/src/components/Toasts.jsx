function Toasts() {
    return (
        <>
            <div className='ea__toaster-wrapper'>
                <div className='toaster-content'>
                    <div className='flex items-center justify-between gap-2 flex-1'>
                        <div className='flex gap-2 items-center'>
                            <img src="../../public/images/success.svg" alt="logo icon"/>
                            <h5>This is success message</h5>
                        </div>
                        <i className='ea-dash-icon ea-close'></i>
                    </div>
                    <div className='flex items-center justify-between gap-2 flex-1'>
                        <div className='flex gap-2 items-center'>
                            <img src="../../public/images/warning.svg" alt="logo icon"/>
                            <h5>This is warning message</h5>
                        </div>
                        <i className='ea-dash-icon ea-close'></i>
                    </div>
                    <div className='flex items-center justify-between gap-2 flex-1'>
                        <div className='flex gap-2 items-center'>
                            <img src="../../public/images/error.svg" alt="logo icon"/>
                            <h5>This is error message</h5>
                        </div>
                        <i className='ea-dash-icon ea-close'></i>
                    </div>
                </div>
            </div>
        </>
    );
}

export default Toasts;