import 'package:flutter/material.dart';
import '../constants/theme.dart';
import '../models/event_model.dart';
import '../models/user_model.dart';
import '../services/api_service.dart';
import 'event_detail_screen.dart';

class ExploreScreen extends StatefulWidget {
  const ExploreScreen({super.key});

  @override
  State<ExploreScreen> createState() => _ExploreScreenState();
}

class _ExploreScreenState extends State<ExploreScreen> {
  final _searchController = TextEditingController();
  String _selectedFilter = 'All'; // 'All', 'Events', 'People', 'Places'
  
  List<EventModel> _events = [];
  List<UserModel> _users = [];
  bool _isLoading = false;

  final List<String> _filters = ['All', 'Events', 'People', 'Places'];

  @override
  void initState() {
    super.initState();
    _fetchResults();
  }

  Future<void> _fetchResults() async {
    setState(() => _isLoading = true);
    final query = _searchController.text.trim();

    final eventsFuture = ApiService.getEvents(search: query.isNotEmpty ? query : null);
    final usersFuture = ApiService.searchUsers(query: query.isNotEmpty ? query : null);

    final results = await Future.wait([eventsFuture, usersFuture]);

    if (mounted) {
      setState(() {
        _events = results[0] as List<EventModel>;
        _users = results[1] as List<UserModel>;
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.background,
      appBar: AppBar(
        title: const Text('Explore'),
      ),
      body: Column(
        children: [
          // Search Bar
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
            child: TextField(
              controller: _searchController,
              onSubmitted: (_) => _fetchResults(),
              decoration: InputDecoration(
                hintText: 'Search events, people, places...',
                prefixIcon: const Icon(Icons.search, color: AppTheme.textSecondary),
                suffixIcon: _searchController.text.isNotEmpty
                    ? IconButton(
                        icon: const Icon(Icons.clear),
                        onPressed: () {
                          _searchController.clear();
                          _fetchResults();
                        },
                      )
                    : null,
              ),
            ),
          ),

          // Filter Chips (All, Events, People, Places)
          SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
            child: Row(
              children: _filters.map((filter) {
                final isSelected = _selectedFilter == filter;
                return Padding(
                  padding: const EdgeInsets.only(right: 8),
                  child: FilterChip(
                    label: Text(filter),
                    selected: isSelected,
                    showCheckmark: false,
                    selectedColor: AppTheme.primary,
                    backgroundColor: Colors.white,
                    labelStyle: TextStyle(
                      color: isSelected ? Colors.white : AppTheme.textPrimary,
                      fontWeight: isSelected ? FontWeight.bold : FontWeight.w500,
                    ),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(20),
                      side: BorderSide(
                        color: isSelected ? AppTheme.primary : const Color(0xFFE5E7EB),
                      ),
                    ),
                    onSelected: (val) {
                      setState(() => _selectedFilter = filter);
                    },
                  ),
                );
              }).toList(),
            ),
          ),
          const SizedBox(height: 8),

          // Results List
          Expanded(
            child: _isLoading
                ? const Center(child: CircularProgressIndicator(color: AppTheme.primary))
                : RefreshIndicator(
                    onRefresh: _fetchResults,
                    color: AppTheme.primary,
                    child: _buildContent(),
                  ),
          ),
        ],
      ),
    );
  }

  Widget _buildContent() {
    if (_selectedFilter == 'People') {
      return _buildPeopleList();
    } else if (_selectedFilter == 'Places') {
      return _buildPlacesList();
    } else if (_selectedFilter == 'Events') {
      return _buildEventsList();
    }

    // Default 'All'
    return ListView(
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
      children: [
        if (_events.isNotEmpty) ...[
          const Text(
            'Activities & Moments',
            style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: AppTheme.textPrimary),
          ),
          const SizedBox(height: 12),
          ..._events.map((e) => _buildEventItem(e)),
          const SizedBox(height: 20),
        ],
        if (_users.isNotEmpty) ...[
          const Text(
            'People',
            style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: AppTheme.textPrimary),
          ),
          const SizedBox(height: 12),
          ..._users.map((u) => _buildUserItem(u)),
        ],
      ],
    );
  }

  Widget _buildEventsList() {
    if (_events.isEmpty) return const Center(child: Text('No events found'));
    return ListView.builder(
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
      itemCount: _events.length,
      itemBuilder: (context, index) => _buildEventItem(_events[index]),
    );
  }

  Widget _buildPeopleList() {
    if (_users.isEmpty) return const Center(child: Text('No people found'));
    return ListView.builder(
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
      itemCount: _users.length,
      itemBuilder: (context, index) => _buildUserItem(_users[index]),
    );
  }

  Widget _buildPlacesList() {
    // Unique locations from events
    final locations = _events.map((e) => e.locationName).toSet().toList();
    if (locations.isEmpty) return const Center(child: Text('No places found'));

    return ListView.builder(
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
      itemCount: locations.length,
      itemBuilder: (context, index) {
        final loc = locations[index];
        return Card(
          margin: const EdgeInsets.only(bottom: 12),
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
          child: ListTile(
            leading: const CircleAvatar(
              backgroundColor: AppTheme.primaryLight,
              child: Icon(Icons.place_rounded, color: AppTheme.primary),
            ),
            title: Text(loc, style: const TextStyle(fontWeight: FontWeight.bold)),
            subtitle: const Text('Popular hangout spot'),
            trailing: const Icon(Icons.arrow_forward_ios_rounded, size: 14),
          ),
        );
      },
    );
  }

  Widget _buildEventItem(EventModel event) {
    return GestureDetector(
      onTap: () {
        Navigator.push(
          context,
          MaterialPageRoute(builder: (_) => EventDetailScreen(eventId: event.id)),
        );
      },
      child: Container(
        margin: const EdgeInsets.only(bottom: 14),
        padding: const EdgeInsets.all(12),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withValues(alpha: 0.03),
              blurRadius: 8,
              offset: const Offset(0, 2),
            ),
          ],
        ),
        child: Row(
          children: [
            ClipRRect(
              borderRadius: BorderRadius.circular(12),
              child: Image.network(
                event.imageUrl ?? 'https://images.unsplash.com/photo-1551632811-561732d1e306?w=200',
                width: 76,
                height: 76,
                fit: BoxFit.cover,
                errorBuilder: (_, _, _) => Container(
                  width: 76,
                  height: 76,
                  color: AppTheme.primaryLight,
                  child: const Icon(Icons.event, color: AppTheme.primary),
                ),
              ),
            ),
            const SizedBox(width: 14),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                    decoration: BoxDecoration(
                      color: AppTheme.primaryLight,
                      borderRadius: BorderRadius.circular(6),
                    ),
                    child: Text(
                      event.category.toUpperCase(),
                      style: const TextStyle(
                        fontSize: 10,
                        fontWeight: FontWeight.w700,
                        color: AppTheme.primary,
                      ),
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    event.title,
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                    style: const TextStyle(fontSize: 15, fontWeight: FontWeight.bold),
                  ),
                  const SizedBox(height: 2),
                  Text(
                    event.locationName,
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                    style: const TextStyle(fontSize: 12, color: AppTheme.textSecondary),
                  ),
                ],
              ),
            ),
            const Icon(Icons.arrow_forward_ios_rounded, size: 14, color: AppTheme.textMuted),
          ],
        ),
      ),
    );
  }

  Widget _buildUserItem(UserModel user) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
      ),
      child: ListTile(
        leading: CircleAvatar(
          radius: 22,
          backgroundImage: NetworkImage(
            user.avatarUrl ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150',
          ),
        ),
        title: Text(user.name, style: const TextStyle(fontWeight: FontWeight.bold)),
        subtitle: Text(user.city ?? 'Malang', style: const TextStyle(fontSize: 12)),
        trailing: OutlinedButton(
          onPressed: () {},
          style: OutlinedButton.styleFrom(
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
          ),
          child: const Text('Connect', style: TextStyle(fontSize: 12, color: AppTheme.primary)),
        ),
      ),
    );
  }
}
