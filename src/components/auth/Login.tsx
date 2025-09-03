'use client';

import React, { useState } from 'react';
import { User, Lock, Eye, EyeOff } from 'lucide-react';
import { useAuth } from '@/context/AuthContext';
import Button from '@/components/windows-xp/Button';

export default function Login() {
    const [credentials, setCredentials] = useState({ username: '', password: '' });
    const [showPassword, setShowPassword] = useState(false);
    const [error, setError] = useState('');
    const { login, isLoading } = useAuth();

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setError('');

        const success = await login(credentials);
        if (!success) {
            setError('Invalid username or password');
        }
    };

    const handleInputChange = (field: string, value: string) => {
        setCredentials(prev => ({ ...prev, [field]: value }));
        if (error) setError('');
    };

    return (
        <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-400 via-blue-500 to-blue-600">
            <div className="xp-window w-full max-w-md">
                <div className="xp-titlebar">
                    <span>Team Manager - Login</span>
                </div>

                <div className="p-6">
                    <div className="text-center mb-6">
                        <h1 className="text-2xl font-bold text-gray-800 mb-2">Welcome Back</h1>
                        <p className="text-gray-600">Sign in to access your dashboard</p>
                    </div>

                    <form onSubmit={handleSubmit} className="space-y-4">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Username
                            </label>
                            <div className="relative">
                                <input
                                    type="text"
                                    value={credentials.username}
                                    onChange={(e) => handleInputChange('username', e.target.value)}
                                    className="w-full p-3 pl-10 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Enter your username"
                                    required
                                />
                                <User className="absolute left-3 top-3.5 h-4 w-4 text-gray-400" />
                            </div>
                        </div>

                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Password
                            </label>
                            <div className="relative">
                                <input
                                    type={showPassword ? 'text' : 'password'}
                                    value={credentials.password}
                                    onChange={(e) => handleInputChange('password', e.target.value)}
                                    className="w-full p-3 pl-10 pr-10 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Enter your password"
                                    required
                                />
                                <Lock className="absolute left-3 top-3.5 h-4 w-4 text-gray-400" />
                                <button
                                    type="button"
                                    onClick={() => setShowPassword(!showPassword)}
                                    className="absolute right-3 top-3.5 h-4 w-4 text-gray-400 hover:text-gray-600"
                                >
                                    {showPassword ? <EyeOff /> : <Eye />}
                                </button>
                            </div>
                        </div>

                        {error && (
                            <div className="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                                {error}
                            </div>
                        )}

                        <Button
                            type="submit"
                            disabled={isLoading}
                            className="w-full justify-center"
                        >
                            {isLoading ? 'Signing in...' : 'Sign In'}
                        </Button>
                    </form>

                    <div className="mt-6 text-center">
                        <p className="text-sm text-gray-600 mb-2">Demo Accounts:</p>
                        <div className="text-xs text-gray-500 space-y-1">
                            <div>Admin: admin / password</div>
                            <div>Investor: saad / password</div>
                            <div>BD: bd / password</div>
                            <div>Developer: abdur / password</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
