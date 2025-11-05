import { useState } from 'react';
import { User } from '../../lib/types';
import { Card } from '../ui/card';
import { Badge } from '../ui/badge';
import { Button } from '../ui/button';
import { Input } from '../ui/input';
import { Search, Mail, Calendar } from 'lucide-react';
import { Avatar, AvatarFallback } from '../ui/avatar';

interface UserManagerProps {
  users: User[];
}

export function UserManager({ users }: UserManagerProps) {
  const [searchTerm, setSearchTerm] = useState('');

  const filteredUsers = users.filter((user) =>
    user.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
    user.email.toLowerCase().includes(searchTerm.toLowerCase())
  );

  return (
    <div className="space-y-6">
      <div className="flex flex-col sm:flex-row justify-between gap-4">
        <h2 className="text-2xl">User Management</h2>
        <div className="relative w-full sm:w-64">
          <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
          <Input
            placeholder="Search users..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="pl-10"
          />
        </div>
      </div>

      <div className="grid grid-cols-1 gap-4">
        {filteredUsers.length === 0 ? (
          <Card className="p-8 text-center text-gray-600">
            No users found
          </Card>
        ) : (
          filteredUsers.map((user) => (
            <Card key={user.id} className="p-6">
              <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div className="flex items-center gap-4">
                  <Avatar className="h-12 w-12">
                    <AvatarFallback className="bg-gray-200">
                      {user.name.split(' ').map(n => n[0]).join('').toUpperCase()}
                    </AvatarFallback>
                  </Avatar>
                  <div>
                    <div className="flex items-center gap-2 mb-1">
                      <h3>{user.name}</h3>
                      <Badge variant={user.role === 'admin' ? 'default' : 'outline'}>
                        {user.role}
                      </Badge>
                    </div>
                    <div className="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 text-sm text-gray-600">
                      <div className="flex items-center gap-1">
                        <Mail className="h-4 w-4" />
                        {user.email}
                      </div>
                      <div className="flex items-center gap-1">
                        <Calendar className="h-4 w-4" />
                        Joined {new Date(user.createdAt).toLocaleDateString()}
                      </div>
                    </div>
                  </div>
                </div>
                <div className="flex gap-2">
                  <Button variant="outline" size="sm">View Profile</Button>
                  <Button variant="outline" size="sm">Edit</Button>
                  {user.role !== 'admin' && (
                    <Button variant="outline" size="sm">Suspend</Button>
                  )}
                </div>
              </div>

              {user.addresses.length > 0 && (
                <div className="border-t mt-4 pt-4">
                  <h4 className="text-sm mb-2">Saved Addresses:</h4>
                  {user.addresses.map((address, idx) => (
                    <p key={idx} className="text-sm text-gray-600">
                      {address.street}, {address.city}, {address.state} {address.zipCode}
                    </p>
                  ))}
                </div>
              )}
            </Card>
          ))
        )}
      </div>

      <div className="flex justify-between items-center">
        <p className="text-sm text-gray-600">
          Showing {filteredUsers.length} of {users.length} users
        </p>
      </div>
    </div>
  );
}
