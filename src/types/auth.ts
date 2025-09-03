export interface User {
    id: string;
    username: string;
    email: string;
    role: 'admin' | 'investor' | 'business_developer' | 'developer';
    name: string;
    avatar?: string;
    isActive: boolean;
    lastLogin?: Date;
    createdAt: Date;
}

export interface LoginCredentials {
    username: string;
    password: string;
}

export interface AuthContextType {
    user: User | null;
    login: (credentials: LoginCredentials) => Promise<boolean>;
    logout: () => void;
    isLoading: boolean;
    isAuthenticated: boolean;
}
