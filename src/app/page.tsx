'use client';

import React, { useState } from 'react';
import { AuthProvider, useAuth } from '@/context/AuthContext';
import Login from '@/components/auth/Login';
import RoleBasedDesktop from '@/components/dashboard/RoleBasedDesktop';
import Taskbar from '@/components/windows-xp/Taskbar';
import RoleBasedStartMenu from '@/components/dashboard/RoleBasedStartMenu';
import Window from '@/components/windows-xp/Window';
import AdminDashboard from '@/components/admin/AdminDashboard';
import InvestorDashboard from '@/components/investor/InvestorDashboard';
import BDDashboard from '@/components/bd/BDDashboard';
import DeveloperDashboard from '@/components/developer/DeveloperDashboard';
import Settings from '@/components/team/Settings';
import Help from '@/components/team/Help';

function MainApp() {
  const { user, logout, isAuthenticated } = useAuth();
  const [startMenuOpen, setStartMenuOpen] = useState(false);
  const [selectedDesktopIcon, setSelectedDesktopIcon] = useState<string | null>(null);
  const [openWindows, setOpenWindows] = useState<Array<{
    id: string;
    title: string;
    isActive: boolean;
    component: React.ReactNode;
  }>>([]);
  const [activeWindow, setActiveWindow] = useState<string | null>(null);

  const handleStartMenuClick = () => {
    setStartMenuOpen(!startMenuOpen);
  };

  const handleMenuItemClick = (itemId: string) => {
    if (itemId === 'logout') {
      logout();
      return;
    }

    const windowId = `window-${itemId}`;

    // Check if window is already open
    const existingWindow = openWindows.find(w => w.id === windowId);
    if (existingWindow) {
      setActiveWindow(windowId);
      return;
    }

    // Create new window based on menu item and user role
    let windowTitle = '';
    let windowComponent: React.ReactNode = null;

    switch (itemId) {
      case 'admin':
        windowTitle = 'User Management';
        windowComponent = <AdminDashboard onClose={() => closeWindow(windowId)} />;
        break;
      case 'investor':
        windowTitle = 'Fund Management';
        windowComponent = <InvestorDashboard onClose={() => closeWindow(windowId)} />;
        break;
      case 'bd':
        windowTitle = 'Business Development Dashboard';
        windowComponent = <BDDashboard onClose={() => closeWindow(windowId)} />;
        break;
      case 'developer':
        windowTitle = 'Developer Dashboard';
        windowComponent = <DeveloperDashboard onClose={() => closeWindow(windowId)} />;
        break;
      case 'team':
        windowTitle = 'Team Overview';
        windowComponent = <div className="p-4">Team overview component coming soon...</div>;
        break;
      case 'finance':
        windowTitle = 'Financial Reports';
        windowComponent = <div className="p-4">Financial reports component coming soon...</div>;
        break;
      case 'upwork':
        windowTitle = 'Upwork Overview';
        windowComponent = <div className="p-4">Upwork overview component coming soon...</div>;
        break;
      case 'linkedin':
        windowTitle = 'LinkedIn Overview';
        windowComponent = <div className="p-4">LinkedIn overview component coming soon...</div>;
        break;
      case 'meetings':
        windowTitle = 'Meetings';
        windowComponent = <div className="p-4">Meetings component coming soon...</div>;
        break;
      case 'settings':
        windowTitle = 'Settings';
        windowComponent = <Settings onClose={() => closeWindow(windowId)} />;
        break;
      case 'help':
        windowTitle = 'Help';
        windowComponent = <Help onClose={() => closeWindow(windowId)} />;
        break;
      default:
        return;
    }

    const newWindow = {
      id: windowId,
      title: windowTitle,
      isActive: true,
      component: windowComponent,
    };

    setOpenWindows(prev =>
      prev.map(w => ({ ...w, isActive: false })).concat(newWindow)
    );
    setActiveWindow(windowId);
  };

  const handleDesktopIconClick = (iconId: string) => {
    setSelectedDesktopIcon(iconId);
    handleMenuItemClick(iconId);
  };

  const closeWindow = (windowId: string) => {
    setOpenWindows(prev => prev.filter(w => w.id !== windowId));
    if (activeWindow === windowId) {
      const remainingWindows = openWindows.filter(w => w.id !== windowId);
      setActiveWindow(remainingWindows.length > 0 ? remainingWindows[0].id : null);
    }
  };

  const minimizeWindow = (windowId: string) => {
    setOpenWindows(prev =>
      prev.map(w => w.id === windowId ? { ...w, isActive: false } : w)
    );
    if (activeWindow === windowId) {
      const otherWindows = openWindows.filter(w => w.id !== windowId);
      setActiveWindow(otherWindows.length > 0 ? otherWindows[0].id : null);
    }
  };

  const maximizeWindow = (windowId: string) => {
    setActiveWindow(windowId);
    setOpenWindows(prev =>
      prev.map(w => ({ ...w, isActive: w.id === windowId }))
    );
  };

  const taskbarWindows = openWindows.map(window => ({
    id: window.id,
    title: window.title,
    isActive: window.isActive,
    onClick: () => maximizeWindow(window.id),
  }));

  if (!isAuthenticated) {
    return <Login />;
  }

  return (
    <div className="h-screen overflow-hidden relative">
      {/* Desktop */}
      <RoleBasedDesktop
        user={user!}
        onIconClick={handleDesktopIconClick}
        selectedIcon={selectedDesktopIcon || undefined}
      />

      {/* Open Windows */}
      {openWindows.map((window) => (
        <Window
          key={window.id}
          title={window.title}
          onClose={() => closeWindow(window.id)}
          onMinimize={() => minimizeWindow(window.id)}
          onMaximize={() => maximizeWindow(window.id)}
        >
          {window.component}
        </Window>
      ))}

      {/* Start Menu */}
      <RoleBasedStartMenu
        isOpen={startMenuOpen}
        onClose={() => setStartMenuOpen(false)}
        onMenuItemClick={handleMenuItemClick}
        user={user!}
      />

      {/* Taskbar */}
      <Taskbar
        onStartMenuClick={handleStartMenuClick}
        openWindows={taskbarWindows}
      />
    </div>
  );
}

export default function Home() {
  return (
    <AuthProvider>
      <MainApp />
    </AuthProvider>
  );
}
