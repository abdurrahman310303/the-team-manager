export interface TeamMember {
    id: string;
    name: string;
    role: 'developer' | 'business_developer' | 'investor';
    email: string;
    phone?: string;
    salary: number;
    joinDate: Date;
    status: 'active' | 'inactive';
    skills?: string[];
    notes?: string;
}

export interface Project {
    id: string;
    name: string;
    description: string;
    client: string;
    budget: number;
    startDate: Date;
    endDate?: Date;
    status: 'planning' | 'active' | 'completed' | 'cancelled';
    assignedMembers: string[];
}

export interface FinancialRecord {
    id: string;
    type: 'income' | 'expense';
    category: 'salary' | 'upwork_connects' | 'project_income' | 'investment' | 'other';
    amount: number;
    description: string;
    date: Date;
    relatedMember?: string;
    relatedProject?: string;
}

export interface UpworkBid {
    id: string;
    title: string;
    description: string;
    client: string;
    budget: number;
    connectsUsed: number;
    bidDate: Date;
    status: 'submitted' | 'interview' | 'hired' | 'rejected';
    assignedBD: string;
}

export interface LinkedInProfile {
    id: string;
    memberId: string;
    profileUrl: string;
    lastOptimized: Date;
    optimizationNotes: string;
    connections: number;
    views: number;
    status: 'optimized' | 'needs_optimization' | 'in_progress';
}
