'use client';

import React, { createContext, useContext, useState, useEffect } from 'react';
import { User, LoginCredentials, AuthContextType } from '@/types/auth';

const AuthContext = createContext<AuthContextType | undefined>(undefined);

// Mock users data - in a real app, this would come from an API
const mockUsers: User[] = [
    {
        id: '1',
        username: 'admin',
        email: 'admin@team.com',
        role: 'admin',
        name: 'System Administrator',
        isActive: true,
        createdAt: new Date('2024-01-01')
    },
    {
        id: '2',
        username: 'saad',
        email: 'saad@team.com',
        role: 'investor',
        name: 'Saad Salman',
        isActive: true,
        createdAt: new Date('2024-01-01')
    },
    {
        id: '3',
        username: 'bd',
        email: 'bd@team.com',
        role: 'business_developer',
        name: 'Business Developer',
        isActive: true,
        createdAt: new Date('2024-02-15')
    },
    {
        id: '4',
        username: 'abdur',
        email: 'abdur@team.com',
        role: 'developer',
        name: 'Abdur Rahman',
        isActive: true,
        createdAt: new Date('2024-01-01')
    },
    {
        id: '5',
        username: 'wasif',
        email: 'wasif@team.com',
        role: 'developer',
        name: 'Wasif Ullah',
        isActive: true,
        createdAt: new Date('2024-01-15')
    },
    {
        id: '6',
        username: 'mubashir',
        email: 'mubashir@team.com',
        role: 'developer',
        name: 'Muhammad Mubashir',
        isActive: true,
        createdAt: new Date('2024-02-01')
    }
];

export function AuthProvider({ children }: { children: React.ReactNode }) {
    const [user, setUser] = useState<User | null>(null);
    const [isLoading, setIsLoading] = useState(true);

    useEffect(() => {
        // Check if user is logged in from localStorage
        const savedUser = localStorage.getItem('user');
        if (savedUser) {
            setUser(JSON.parse(savedUser));
        }
        setIsLoading(false);
    }, []);

    const login = async (credentials: LoginCredentials): Promise<boolean> => {
        setIsLoading(true);

        // Simulate API call delay
        await new Promise(resolve => setTimeout(resolve, 1000));

        // Find user by username (in real app, this would be an API call)
        const foundUser = mockUsers.find(u =>
            u.username === credentials.username &&
            credentials.password === 'password' // Simple password for demo
        );

        if (foundUser) {
            const userWithLogin = { ...foundUser, lastLogin: new Date() };
            setUser(userWithLogin);
            localStorage.setItem('user', JSON.stringify(userWithLogin));
            setIsLoading(false);
            return true;
        }

        setIsLoading(false);
        return false;
    };

    const logout = () => {
        setUser(null);
        localStorage.removeItem('user');
    };

    const value: AuthContextType = {
        user,
        login,
        logout,
        isLoading,
        isAuthenticated: !!user
    };

    return (
        <AuthContext.Provider value={value}>
            {children}
        </AuthContext.Provider>
    );
}

export function useAuth() {
    const context = useContext(AuthContext);
    if (context === undefined) {
        throw new Error('useAuth must be used within an AuthProvider');
    }
    return context;
}
