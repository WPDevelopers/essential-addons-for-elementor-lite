import React, { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';
import App from './App';

document.addEventListener('DOMContentLoaded', function () { 
    const quickSetupWrapper = document.getElementById('eael-onboard--wrapper');
    const root = createRoot( quickSetupWrapper );
    
    root.render(
      <StrictMode>
        <App />
      </StrictMode>
    );
});