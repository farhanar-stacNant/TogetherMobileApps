import 'package:flutter/material.dart';
import '../constants/theme.dart';
import '../models/chat_model.dart';
import '../services/api_service.dart';
import 'chat_room_screen.dart';

class ChatsScreen extends StatefulWidget {
  const ChatsScreen({super.key});

  @override
  State<ChatsScreen> createState() => _ChatsScreenState();
}

class _ChatsScreenState extends State<ChatsScreen> {
  List<ConversationModel> _conversations = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadChats();
  }

  Future<void> _loadChats() async {
    setState(() => _isLoading = true);
    final list = await ApiService.getConversations();
    if (mounted) {
      setState(() {
        _conversations = list;
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.background,
      appBar: AppBar(
        title: const Text('Chats'),
        actions: [
          IconButton(
            icon: const Icon(Icons.edit_square, color: AppTheme.primary),
            onPressed: () {
              ScaffoldMessenger.of(context).showSnackBar(
                const SnackBar(content: Text('Select a connection to start a new chat')),
              );
            },
          ),
          const SizedBox(width: 8),
        ],
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator(color: AppTheme.primary))
          : RefreshIndicator(
              onRefresh: _loadChats,
              color: AppTheme.primary,
              child: _conversations.isEmpty
                  ? const Center(
                      child: Text(
                        'No messages yet. Connect with people to chat!',
                        style: TextStyle(color: AppTheme.textSecondary),
                      ),
                    )
                  : ListView.separated(
                      padding: const EdgeInsets.symmetric(vertical: 8),
                      itemCount: _conversations.length,
                      separatorBuilder: (_, _) => const Divider(
                        height: 1,
                        indent: 80,
                        color: AppTheme.divider,
                      ),
                      itemBuilder: (context, index) {
                        final conv = _conversations[index];
                        final hasUnread = conv.unreadCount > 0;

                        return ListTile(
                          contentPadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 6),
                          leading: Stack(
                            children: [
                              CircleAvatar(
                                radius: 26,
                                backgroundColor: AppTheme.primaryLight,
                                backgroundImage: NetworkImage(
                                  conv.displayAvatar ??
                                      'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150',
                                ),
                              ),
                              if (conv.type == 'group')
                                Positioned(
                                  right: 0,
                                  bottom: 0,
                                  child: Container(
                                    padding: const EdgeInsets.all(2),
                                    decoration: const BoxDecoration(
                                      color: AppTheme.primary,
                                      shape: BoxShape.circle,
                                    ),
                                    child: const Icon(
                                      Icons.group,
                                      size: 10,
                                      color: Colors.white,
                                    ),
                                  ),
                                ),
                            ],
                          ),
                          title: Text(
                            conv.displayTitle,
                            style: TextStyle(
                              fontWeight: hasUnread ? FontWeight.w800 : FontWeight.w600,
                              fontSize: 16,
                              color: AppTheme.textPrimary,
                            ),
                          ),
                          subtitle: Text(
                            conv.lastMessage?.message ?? 'No messages yet',
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                            style: TextStyle(
                              color: hasUnread ? AppTheme.textPrimary : AppTheme.textSecondary,
                              fontWeight: hasUnread ? FontWeight.w600 : FontWeight.normal,
                              fontSize: 13,
                            ),
                          ),
                          trailing: Column(
                            mainAxisAlignment: MainAxisAlignment.center,
                            crossAxisAlignment: CrossAxisAlignment.end,
                            children: [
                              Text(
                                conv.lastMessage != null
                                    ? '${conv.lastMessage!.createdAt.hour.toString().padLeft(2, '0')}:${conv.lastMessage!.createdAt.minute.toString().padLeft(2, '0')}'
                                    : '',
                                style: TextStyle(
                                  fontSize: 12,
                                  color: hasUnread ? AppTheme.primary : AppTheme.textMuted,
                                  fontWeight: hasUnread ? FontWeight.bold : FontWeight.normal,
                                ),
                              ),
                              const SizedBox(height: 4),
                              if (hasUnread)
                                Container(
                                  padding: const EdgeInsets.symmetric(horizontal: 7, vertical: 2),
                                  decoration: BoxDecoration(
                                    color: AppTheme.primary,
                                    borderRadius: BorderRadius.circular(10),
                                  ),
                                  child: Text(
                                    '${conv.unreadCount}',
                                    style: const TextStyle(
                                      color: Colors.white,
                                      fontSize: 11,
                                      fontWeight: FontWeight.bold,
                                    ),
                                  ),
                                )
                              else
                                const SizedBox(height: 16),
                            ],
                          ),
                          onTap: () {
                            Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (_) => ChatRoomScreen(conversation: conv),
                              ),
                            ).then((_) => _loadChats());
                          },
                        );
                      },
                    ),
            ),
    );
  }
}
