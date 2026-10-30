export type Locale = 'en' | 'id' | 'ar';
export type Role = 'participant' | 'presenter' | 'reviewer' | 'cohost_pic' | 'superadmin';
export interface SharedProps {
    [key: string]: unknown;
    locale: Locale;
    direction: 'ltr' | 'rtl';
    supportedLocales: Locale[];
}
export interface Workspace {
    key: string;
    label: string;
    role: Role | null;
    event: string | null;
    institution: string | null;
}
export interface DashboardProps extends SharedProps {
    account: { name: string; email: string };
    workspaces: Workspace[];
    activeWorkspace: string;
}

export function cmsEligible(workspaces: Workspace[], selected: string): boolean {
    return /^assignment:[1-9][0-9]*$/.test(selected) && workspaces.some(choice => choice.key === selected
        && choice.role === 'superadmin' && choice.event !== null && choice.institution === null);
}
